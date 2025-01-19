<?php

namespace Tests\Data;

use TempMailIo\TempMailPhp\Data;
use TempMailIo\TempMailPhp\Email\Data\Request\DomainType;

class ExtendedDataWithEnum extends Data
{
    public ?string $key1 = null;

    public ?string $key2 = null;

    public ?DomainType $domainType = null;
}
