<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;
use TempMailIo\TempMailPhp\GenericData\SuccessResponse;

class DeleteResponse extends Data
{
    public ?SuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}