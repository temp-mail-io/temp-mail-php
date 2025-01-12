<?php

namespace TempMailIo\TempMailPhp\RateLimit;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\RateLimit\Data\Response\GetStatusResponse;

interface ClientInterface
{
    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getStatus(): GetStatusResponse;
}