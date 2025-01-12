<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Domain\Data\Response;

use TempMailIo\TempMailPhp\GenericData\SuccessResponse;

class GetAvailableDomainsSuccessResponse extends SuccessResponse
{
    /**
     * @var \TempMailIo\TempMailPhp\Domain\Data\Response\Domain[]
     */
    public array $domains = [];
}