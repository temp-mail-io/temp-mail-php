<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\RateLimit\Data\Response;

use TempMailIo\TempMailPhp\Data;

class GetStatusSuccessResponse extends Data
{
    public int $limit;

    public int $remaining;

    public int $used;

    public int $reset;
}
