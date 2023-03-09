<?php

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
