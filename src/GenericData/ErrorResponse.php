<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class ErrorResponse extends Data
{
    public Error $error;

    public ErrorMeta $meta;
}
