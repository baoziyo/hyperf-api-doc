<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:16:25
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Response;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\Example\DTO\Address;

class Contact
{
    #[ApiModelProperty('名称')]
    public string $name;

    #[ApiModelProperty('年龄')]
    public ?int $age = null;

    #[ApiModelProperty('城市')]
    public ?City $city = null;

    #[ApiModelProperty('城市1')]
    public ?City1 $city1 = null;

    /**
     * 需要绝对路径.
     * @var Address[]
     */
    #[ApiModelProperty('地址')]
    public array $addressArr;

    #[ApiModelProperty('数组')]
    private ?array $arr;

    public function getArr(): ?array
    {
        return $this->arr;
    }

    public function setArr(?array $arr): void
    {
        $this->arr = $arr;
    }
}
