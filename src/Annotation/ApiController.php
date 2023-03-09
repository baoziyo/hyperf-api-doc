<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Attribute;
#[Attribute(Attribute::TARGET_CLASS)]
class ApiController
{
    public function __construct(public string $prefix = '')
    {
    }
}
