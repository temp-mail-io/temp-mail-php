<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class Attachment extends Data
{
    public string $id;

    public string $name;

    public float $size;
}