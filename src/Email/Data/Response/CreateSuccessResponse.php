<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email\Data\Response;

use TempMailIo\TempMailPhp\GenericData\SuccessResponse as GenericSuccessResponse;

final class CreateSuccessResponse extends GenericSuccessResponse
{
    public string $email;

    public int $ttl;
}
