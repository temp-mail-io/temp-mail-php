<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email\Data\Request;

enum DomainType: string
{
    case PUBLIC = 'public';

    case CUSTOM = 'custom';

    case PREMIUM = 'premium';
}
