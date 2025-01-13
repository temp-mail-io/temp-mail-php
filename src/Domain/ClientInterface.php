<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Domain;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Domain\Data\Response\GetAvailableDomainResponse;

interface ClientInterface
{
    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getAvailableDomains(): GetAvailableDomainResponse;
}
