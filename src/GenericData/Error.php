<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class Error extends Data
{
    public string $type;

    public string $code;

    public string $detail;
}