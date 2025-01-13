<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp;

use GuzzleHttp\Client;
use TempMailIo\TempMailPhp\Email\Client as EmailClient;
use TempMailIo\TempMailPhp\Email\ClientInterface as EmailClientInterface;
use TempMailIo\TempMailPhp\Domain\Client as DomainClient;
use TempMailIo\TempMailPhp\Domain\ClientInterface as DomainClientInterface;
use TempMailIo\TempMailPhp\RateLimit\Client as RateLimitClient;
use TempMailIo\TempMailPhp\RateLimit\ClientInterface as RateLimitClientInterface;
use TempMailIo\TempMailPhp\Message\Client as MessageClient;
use TempMailIo\TempMailPhp\Message\ClientInterface as MessageClientInterface;

class Factory
{
    public static function createEmailClient(string $apiKey): EmailClientInterface
    {
        return new EmailClient(new Client(), new RateLimitReader(), $apiKey);
    }

    public static function createDomainClient(string $apiKey): DomainClientInterface
    {
        return new DomainClient(new Client(), new RateLimitReader(), $apiKey);
    }

    public static function createRateLimitClient(string $apiKey): RateLimitClientInterface
    {
        return new RateLimitClient(new Client(), $apiKey);
    }

    public static function createMessageClient(string $apiKey): MessageClientInterface
    {
        return new MessageClient(new Client(), new RateLimitReader(), $apiKey);
    }
}
