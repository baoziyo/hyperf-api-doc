<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:13:49
 * ChangeTime: 2023-04-26 10:21:30
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\Enum;

enum StatusEnum: string
{
    case SUCCESS = 'success';

    case CLOSED = 'closed';
}
