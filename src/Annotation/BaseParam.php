<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-01-17 02:11:40
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Hyperf\Di\Annotation\AbstractMultipleAnnotation;
use OpenApi\Generator;

abstract class BaseParam extends AbstractMultipleAnnotation
{
    protected string $in;

    public function __construct(
        public string $name,
        public ?bool $required = null,
        public string $type = 'string',
        public $default = Generator::UNDEFINED,
        public string $description = Generator::UNDEFINED,
        public $format = Generator::UNDEFINED,
        public bool $hidden = false
    ) {
    }

    public function getIn(): string
    {
        return $this->in;
    }
}
