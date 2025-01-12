<?php

namespace Tests\Data;

use TempMailIo\TempMailPhp\Data;

class ExtendedDataWithNestedListOfObjects extends Data
{
    public ?string $key1 = null;

    public ?string $key2 = null;

    /** @var \Tests\Data\ExtendedData[] */
    public array $data = [];
}