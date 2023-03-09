<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Annotation;

abstract class ApiMapping
{
    public function __construct(public ?string $path = null, public array $methods = [])
    {
    }
}
