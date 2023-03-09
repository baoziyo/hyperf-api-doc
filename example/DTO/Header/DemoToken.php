<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Header;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Between;

class DemoToken
{
    #[ApiModelProperty(value: '名称', required: true)]
    public string $name;

    #[Between(2, 10)]
    public string $token;

    #[ApiModelProperty(value: '名称', required: true)]
    public int $age;
}
