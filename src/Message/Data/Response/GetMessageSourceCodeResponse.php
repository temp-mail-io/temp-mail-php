<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class GetMessageSourceCodeResponse extends Data
{
    public ?GetMessageSourceCodeSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}
