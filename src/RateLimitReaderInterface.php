<?php

namespace TempMailIo\TempMailPhp;

use TempMailIo\TempMailPhp\GenericData\RateLimit;

interface RateLimitReaderInterface
{
    /**
     * @throws \ReflectionException
     */
    public function createRateLimitFromHeaders(array $headers): RateLimit;
}
