<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class SuccessResponse extends Data
{
    public RateLimit $rateLimit;
}
