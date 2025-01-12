<?php

namespace Tests\Data;

use TempMailIo\TempMailPhp\Data;

class ExtendedDataWithNestedObject extends Data
{
    public ?string $key3 = null;

    public ?string $key4 = null;

    public ?ExtendedData $extendedData = null;
}