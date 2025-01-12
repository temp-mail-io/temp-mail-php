<?php

namespace Tests\Domain;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Domain\Client;
use TempMailIo\TempMailPhp\Domain\Data\Response\GetAvailableDomainResponse;
use TempMailIo\TempMailPhp\RateLimitReader;

class ClientGetAvailableDomainsTest extends TestCase
{
    public function testGetAvailableDomainsSuccess(): void
    {
        $domains = [
            [
                'name' => 'test1.com',
                'type' => 'public',
            ],
            [
                'name' => 'test2.com',
                'type' => 'premium',
            ],
            [
                'name' => 'test3.com',
                'type' => 'custom',
            ],
        ];

        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
            ], json_encode(['domains' => $domains]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), 'test-api-key');

        $response = $client->getAvailableDomains();

        $this->assertInstanceOf(GetAvailableDomainResponse::class, $response);
        $this->assertNotNull($response->successResponse);
        $this->assertEquals($domains, $response->successResponse->toArray()['domains']);
        $this->assertEquals([
            'limit' => '100',
            'remaining' => '99',
            'used' => '1',
            'reset' => '3600',
        ], $response->successResponse->rateLimit->toArray());
    }

    public function testGetAvailableDomains400Error(): void
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

        $response = $client->getAvailableDomains();

        $this->assertInstanceOf(GetAvailableDomainResponse::class, $response);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testGetAvailableDomains429Error(): void
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

        $response = $client->getAvailableDomains();

        $this->assertInstanceOf(GetAvailableDomainResponse::class, $response);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }
}
