<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Request;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Between;

class DemoFormData
{
    #[ApiModelProperty(value: '名称', required: true)]
    public string $name;

    #[Between(2, 10)]
    public int $num;

    #[ApiModelProperty(value: '年龄', required: true)]
    public int $age;
}
