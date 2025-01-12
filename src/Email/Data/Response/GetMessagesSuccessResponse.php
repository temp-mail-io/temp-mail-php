<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email\Data\Response;

use TempMailIo\TempMailPhp\GenericData\SuccessResponse;

class GetMessagesSuccessResponse extends SuccessResponse
{
    /**
     * @var \TempMailIo\TempMailPhp\GenericData\Message[]
     */
    public array $messages = [];
}
