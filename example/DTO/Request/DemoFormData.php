<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:18:25
 * ChangeTime: 2023-04-26 10:21:29
 */

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
