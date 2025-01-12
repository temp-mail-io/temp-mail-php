<?php

namespace TempMailIo\TempMailPhp\RateLimit\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class GetStatusResponse extends Data
{
    public ?GetStatusSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}