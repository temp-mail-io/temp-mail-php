<?php

namespace TempMailIo\TempMailPhp\Domain\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class GetAvailableDomainResponse extends Data
{
    public ?GetAvailableDomainsSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}