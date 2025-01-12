<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email\Data\Request;

use TempMailIo\TempMailPhp\Data;

final class CreateRequest extends Data
{
    public ?string $email = null;
    public ?string $domain = null;
    public ?string $domainType = null;
}