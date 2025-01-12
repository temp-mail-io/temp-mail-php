<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class RateLimit extends Data
{
    public int $limit;

    public int $remaining;

    public int $used;

    public int $reset;
}