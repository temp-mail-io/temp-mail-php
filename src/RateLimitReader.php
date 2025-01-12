<?php

namespace TempMailIo\TempMailPhp;

use TempMailIo\TempMailPhp\GenericData\RateLimit;

class RateLimitReader
{
    /**
     * @throws \ReflectionException
     */
    public function createRateLimitFromHeaders(array $headers): RateLimit
    {
        return RateLimit::create()
            ->fromArray([
                'limit' => (int)$headers[Constants::RATE_LIMIT_HEADER][0],
                'remaining' => (int)$headers[Constants::RATE_REMAINING_HEADER][0],
                'used' => (int)$headers[Constants::RATE_USED_HEADER][0],
                'reset' => (int)$headers[Constants::RATE_RESET_HEADER][0],
            ]);
    }
}
