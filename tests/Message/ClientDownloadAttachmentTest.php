<?php

namespace Tests\Message;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Message\Client;
use TempMailIo\TempMailPhp\Message\Data\Response\DownloadAttachmentResponse;
use TempMailIo\TempMailPhp\Message\File\Writer;
use TempMailIo\TempMailPhp\RateLimitReader;
use Tests\HeadersHelper;

class ClientDownloadAttachmentTest extends TestCase
{
    public function testDownloadAttachmentSuccess(): void
    {
        $string = 'Hello, World!';

        $mock = new MockHandler([
            new Response(200, [
                'X-Ratelimit-Limit' => '100',
                'X-Ratelimit-Remaining' => '99',
                'X-Ratelimit-Used' => '1',
                'X-Ratelimit-Reset' => '3600',
                'Content-Type' => 'application/octet-stream'
            ], $string)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $root = vfsStream::setup();
        $file = vfsStream::newFile('file_path.txt')->at($root);

        $response = $client->downloadAttachment('abc', $file->url());

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/octet-stream',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DownloadAttachmentResponse::class, $response);
        $this->assertNotNull($response->successResponse);
        $this->assertNull($response->errorResponse);
        $this->assertEquals('vfs://root/file_path.txt', $response->successResponse->filePathName);
        $this->assertEquals([
            'limit' => '100',
            'remaining' => '99',
            'used' => '1',
            'reset' => '3600',
        ], $response->successResponse->toArray()['rate_limit']);
        $this->assertStringEqualsFile($file->url(), $string);
    }

    public function testDownloadAttachment400Error(): void
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

        $response = $client->downloadAttachment('abc', '/root/file_path.txt');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/octet-stream',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DownloadAttachmentResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testDownloadAttachment429Error(): void
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

        $response = $client->downloadAttachment('abc', '/root/file_path.txt');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/octet-stream',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));

        $this->assertInstanceOf(DownloadAttachmentResponse::class, $response);
        $this->assertNull($response->successResponse);
        $this->assertEquals($error, $response->errorResponse->toArray());
    }

    public function testDownloadAttachment502Error(): void
    {
        $this->expectException(ServerException::class);

        $mock = new MockHandler([
            new Response(502, [], 'Bad Gateway')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        $client = new Client($guzzleClient, new RateLimitReader(), new Writer(), 'test-api-key');

        $client->downloadAttachment('abc', '/root/file_path.txt');

        $this->assertEqualsCanonicalizing([
            'Host' => 'api.temp-mail.io',
            'X-API-Key' => 'test-api-key',
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/octet-stream',
            'User-Agent' => 'temp-mail-php/v1.0.0',
        ], HeadersHelper::getHeadersFromRequest($mock->getLastRequest()));
    }
}
