<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message\Data\Response;

use TempMailIo\TempMailPhp\GenericData\SuccessResponse;

class GetMessageSourceCodeSuccessResponse extends SuccessResponse
{
    public string $data;
}
