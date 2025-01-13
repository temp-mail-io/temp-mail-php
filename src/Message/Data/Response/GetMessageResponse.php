<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class GetMessageResponse extends Data
{
    public ?GetMessageSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}
