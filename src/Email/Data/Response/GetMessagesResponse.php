<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class GetMessagesResponse extends Data
{
    public ?GetMessagesSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}