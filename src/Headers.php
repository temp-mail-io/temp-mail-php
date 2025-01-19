<?php

namespace TempMailIo\TempMailPhp;

class Headers
{
    public static function getDefaultJsonRequestHeaders(string $apiKey): array
    {
        return [
            Constants::API_KEY_HEADER => $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => Constants::USER_AGENT_HEADER_VALUE,
        ];
    }

    public static function getDefaultOctetStreamHeaders(string $apiKey): array
    {
        return [
            Constants::API_KEY_HEADER => $apiKey,
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/octet-stream',
            'User-Agent' => Constants::USER_AGENT_HEADER_VALUE,
        ];
    }
}