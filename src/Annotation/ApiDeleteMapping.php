<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ApiDeleteMapping extends ApiMapping
{
    public function __construct(?string $path = null)
    {
        parent::__construct($path, ['DELETE']);
    }
}
