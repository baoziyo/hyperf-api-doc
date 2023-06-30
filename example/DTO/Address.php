<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 11:26:41
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Required;

class Address
{
    public string $street;

    #[ApiModelProperty('浮点数')]
    public float $float;

    public int $int;

    /** @var array<int,string> */
    public array $array;

    #[ApiModelProperty('城市')]
    #[Required]
    public ?City $city = null;
}
