<?php

namespace Tests\Message;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Message\Client;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageSourceCodeResponse;
use TempMailIo\TempMailPhp\Message\File\Writer;
use TempMailIo\TempMailPhp\RateLimitReader;

class ClientGetMessageSourceCodeTest extends TestCase
{
    public function testGetMessageSourceCodeSuccess(): void
    {
        $data = 'Hello, World!';

        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
            ], json_encode(['data' => $data]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $response = $client->getMessageSourceCode('abc');

        $this->assertInstanceOf(GetMessageSourceCodeResponse::class, $response);
        $this->assertNotNull($response->successResponse);
        $this->assertNull($response->errorResponse);
        $this->assertEquals($data, $response->successResponse->toArray()['data']);
        $this->assertEquals([
            'limit' => '100',
            'remaining' => '99',
            'used' => '1',
            'reset' => '3600',
        ], $response->successResponse->toArray()['rate_limit']);
    }

    public function testGetMessageSourceCode400Error(): void
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

        $response = $client->getMessageSourceCode('abc');

        $this->assertInstanceOf(GetMessageSourceCodeResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testGetMessageSourceCode429Error(): void
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

        $response = $client->getMessageSourceCode('abc');

        $this->assertInstanceOf(GetMessageSourceCodeResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testGetMessageSourceCode502Error(): void
    {
        $this->expectException(ServerException::class);

        $mock = new MockHandler([
            new Response(502, [], 'Bad Gateway')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $client->getMessageSourceCode('abc');
    }
}
