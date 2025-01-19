<?php

namespace Tests\Message;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Message\Client;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageResponse;
use TempMailIo\TempMailPhp\Message\File\Writer;
use TempMailIo\TempMailPhp\RateLimitReader;
use Tests\HeadersHelper;

class ClientGetMessageTest extends TestCase
{
    public function testGetMessageSuccess(): void
    {
        $message = [
            'id' => 'abc',
            'from' => 'test@test.com',
            'to' => 'test@example.com',
            'cc' => [],
            'subject' => 'Test',
            'body_text' => 'Text',
            'body_html' => 'HTML',
            'created_at' => '2025-01-11T20:44:25.36632Z',
            'attachments' => [],
        ];

        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
            ], json_encode($message))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $response = $client->getMessage('abc');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(GetMessageResponse::class, $response);
        $this->assertNotNull($response->successResponse);
        $this->assertNull($response->errorResponse);
        $this->assertEquals($message, $response->successResponse->message->toArray());
        $this->assertEquals([
            'limit' => '100',
            'remaining' => '99',
            'used' => '1',
            'reset' => '3600',
        ], $response->successResponse->toArray()['rate_limit']);
    }

    public function testGetMessage400Error(): void
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
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $response = $client->getMessage('abc');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(GetMessageResponse::class, $response);
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
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $response = $client->getMessage('abc');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(GetMessageResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testCreate502Error(): void
    {
        $this->expectException(ServerException::class);

        $mock = new MockHandler([
            new Response(502, [], 'Bad Gateway')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $client->getMessage('abc');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));
    }
}
