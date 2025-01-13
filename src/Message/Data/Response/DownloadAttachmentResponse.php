<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;

class DownloadAttachmentResponse extends Data
{
    public ?DownloadAttachmentSuccessResponse $successResponse = null;

    public ?ErrorResponse $errorResponse = null;
}
