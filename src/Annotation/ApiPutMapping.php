<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-02-28 03:00:28
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ApiPutMapping extends ApiMapping
{
    public function __construct(?string $path = null)
    {
        parent::__construct($path, ['PUT']);
    }
}
