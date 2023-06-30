<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-01-17 02:11:47
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS)]
class Api extends AbstractAnnotation
{
    public function __construct(public mixed $tags = null, public string $description = '', public int $position = 0, public bool $hidden = false)
    {
    }
}
