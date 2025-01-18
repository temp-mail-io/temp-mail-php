<?php

namespace Tests\RateLimit;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\RateLimit\Client;
use TempMailIo\TempMailPhp\RateLimit\Data\Response\GetStatusResponse;

class ClientGetStatusTest extends TestCase
{
    public function testGetStatusSuccess(): void
    {
        $status = [
            'limit' => '100',
            'remaining' => '99',
            'used' => '1',
            'reset' => '3600',
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($status))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, 'test-api-key');

        $response = $client->getStatus();

        $this->assertInstanceOf(GetStatusResponse::class, $response);
        $this->assertNull($response->errorResponse);
        $this->assertNotNull($response->successResponse);
        $this->assertEquals($status, $response->successResponse->toArray());
    }

    public function testGetStatus400Error(): void
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
        $client = new Client($guzzleClient, 'test-api-key');

        $response = $client->getStatus();

        $this->assertInstanceOf(GetStatusResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testGetStatus502Error(): void
    {
        $this->expectException(ServerException::class);

        $mock = new MockHandler([
            new Response(502, [], 'Bad Gateway')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, 'test-api-key');

        $client->getStatus();
    }
}
