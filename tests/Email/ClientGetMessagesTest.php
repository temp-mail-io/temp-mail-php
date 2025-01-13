<?php

namespace Tests\Email;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Email\Client;
use TempMailIo\TempMailPhp\Email\Data\Response\GetMessagesResponse;
use TempMailIo\TempMailPhp\RateLimitReader;

class ClientGetMessagesTest extends TestCase
{
    public function testGetMessagesSuccess(): void
    {
        $messages = [
            'messages' => [
                [
                    'id' => 'abc',
                    'from' => 'test@test.com',
                    'to' => 'test@example.com',
                    'cc' => [],
                    'subject' => 'Test',
                    'body_text' => 'Text',
                    'body_html' => 'HTML',
                    'created_at' => '2025-01-11T20:44:25.36632Z',
                ],
                [
                    'id' => 'abc123',
                    'from' => 'test@test.com',
                    'to' => 'test@example.com',
                    'cc' => [],
                    'subject' => 'Test1',
                    'body_text' => 'Text1',
                    'body_html' => 'HTML1',
                    'created_at' => '2025-01-12T20:44:25.36632Z',
                    'attachments' => [
                        [
                            'id' => 'abc123',
                            'name' => 'test.txt',
                            'size' => 123,
                        ],
                        [
                            'id' => 'abc123',
                            'name' => 'test.txt',
                            'size' => 123.33,
                        ],
                    ],
                ],
            ],
        ];

        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
            ], json_encode($messages))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $response = $client->getMessages('test@example.com');

        $this->assertInstanceOf(GetMessagesResponse::class, $response);
        $this->assertNotNull($response->successResponse);
        $this->assertNull($response->errorResponse);
        $this->assertEquals([
            'id' => 'abc',
            'from' => 'test@test.com',
            'to' => 'test@example.com',
            'cc' => [],
            'subject' => 'Test',
            'body_text' => 'Text',
            'body_html' => 'HTML',
            'created_at' => '2025-01-11T20:44:25.36632Z',
            'attachments' => [],
        ], $response->successResponse->toArray()['messages'][0]);
        $this->assertEquals([
            'id' => 'abc123',
            'from' => 'test@test.com',
            'to' => 'test@example.com',
            'cc' => [],
            'subject' => 'Test1',
            'body_text' => 'Text1',
            'body_html' => 'HTML1',
            'created_at' => '2025-01-12T20:44:25.36632Z',
            'attachments' => [
                [
                    'id' => 'abc123',
                    'name' => 'test.txt',
                    'size' => 123,
                ],
                [
                    'id' => 'abc123',
                    'name' => 'test.txt',
                    'size' => 123.33,
                ]
            ],
        ], $response->successResponse->toArray()['messages'][1]);
    }

    public function testCreate400Error(): void
    {
        $error = [
            'error' => [
                'type' => 'request_error',
                'code' => 'api_key_invalid',
                'detail' => 'API token is invalid'
            ],
            'meta' => [
                'request_id' => '01JHB0QRKWRM8C9T9T4FNRDW3Y'
            ]
        ];

        $mock = new MockHandler([
            new Response(400, [], json_encode($error))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $response = $client->getMessages('test@example.com');

        $this->assertInstanceOf(GetMessagesResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testCreate429Error(): void
    {
        $error = [
            'error' => [
                'type' => 'request_error',
                'code' => 'rate_limit_exceeded',
                'detail' => 'Rate limit exceeded'
            ],
            'meta' => [
                'request_id' => '01JHB0QRKWRM8C9T9T4FNRDW3Y'
            ]
        ];

        $mock = new MockHandler([
            new Response(429, [], json_encode($error))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $response = $client->getMessages('test@example.com');

        $this->assertInstanceOf(GetMessagesResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }
}
