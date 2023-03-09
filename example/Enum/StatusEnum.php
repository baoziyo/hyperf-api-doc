<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\Enum;

enum StatusEnum: string
{
    case SUCCESS = 'success';

    case CLOSED = 'closed';
}
