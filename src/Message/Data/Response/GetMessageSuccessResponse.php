<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\GenericData\Message;
use TempMailIo\TempMailPhp\GenericData\SuccessResponse;

class GetMessageSuccessResponse extends SuccessResponse
{
    public Message $message;
}
