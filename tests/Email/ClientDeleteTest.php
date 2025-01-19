<?php

namespace Tests\Email;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Email\Client;
use TempMailIo\TempMailPhp\Email\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\RateLimitReader;
use Tests\HeadersHelper;

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

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertNull($response->errorResponse);
        $this->assertNotNull($response->successResponse);
    }

    public function testDelete400Error(): void
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

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testDelete429Error(): void
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

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testDelete502Error(): void
    {
        $this->expectException(ServerException::class);

        $mock = new MockHandler([
            new Response(502, [], 'Bad Gateway')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $client->delete('abc');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));
    }
}
