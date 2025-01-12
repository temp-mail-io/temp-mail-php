<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp;

interface Constants
{
    public const API_V1_URL = 'https://api.temp-mail.io/v1';

    public const API_KEY_HEADER = 'X-API-Key';

    public const RATE_LIMIT_HEADER = 'X-Ratelimit-Limit';

    public const RATE_REMAINING_HEADER = 'X-Ratelimit-Remaining';

    public const RATE_USED_HEADER = 'X-Ratelimit-Used';

    public const RATE_RESET_HEADER = 'X-Ratelimit-Reset';
}
