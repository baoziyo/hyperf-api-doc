<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-01-17 02:12:27
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;
use OpenApi\Generator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ApiModelProperty extends AbstractAnnotation
{
    public function __construct(
        public ?string $value = null,
        public mixed $example = Generator::UNDEFINED,
        public bool $hidden = false,
        public ?bool $required = null
    ) {
    }
}
