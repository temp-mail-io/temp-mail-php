<?php

namespace Tests\Email;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Email\Client;
use TempMailIo\TempMailPhp\Email\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\RateLimitReader;

class ClientDeleteTest extends TestCase
{
    public function testDeleteSuccess(): void
    {
        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
            ], '')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $response = $client->delete('test@example.com');

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertNull($response->errorResponse);
        $this->assertNotNull($response->successResponse);
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

        $response = $client->delete('test@example.com');

        $this->assertInstanceOf(DeleteResponse::class, $response);
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

        $response = $client->delete('test@example.com');

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }
}
