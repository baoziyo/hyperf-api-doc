<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Request;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Annotation\ArrayType;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Between;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Email;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\In;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Integer;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Nullable;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Required;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Validation;
use Baoziyoo\Hyperf\DTO\SimpleType;
use Baoziyoo\Hyperf\Example\DTO\Address;
use Baoziyoo\Hyperf\Example\Enum\StatusEnum;

class DemoBodyRequest
{
    #[ApiModelProperty('int数组')]
    #[Required]
    #[ArrayType(SimpleType::INT)]
    public array $intArr;

    #[ApiModelProperty('demo名称')]
    public ?string $demoName = 'demo';

    #[ApiModelProperty('枚举')]
//    #[In(DemoBodyRequest::IN)]
    #[Nullable]
    public StatusEnum $enum;

    #[ApiModelProperty('价格')]
    #[Required]
    public float $price;

    #[ApiModelProperty('电子邮件', example: '1@e.com')]
    #[Email(messages: '请输入正确的电子邮件')]
    public string $email;

    /**
     * @var int[]
     */
    #[ApiModelProperty('int数据')]
    #[Validation(rule: 'array')]
    public array $intIdList;

    #[ApiModelProperty('addArr')]
    #[Validation(rule: 'array')]
    #[ArrayType(Address::class)]
    public array $addArr;

    public object $obj;

    #[Integer]
    #[Between(min: 2, max: 10)]
    public int $num = 6;
}
