<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Request;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Between;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\In;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Integer;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Max;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Regex;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Required;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\StartsWith;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Str;
use Baoziyoo\Hyperf\Example\DTO\PageQuery;
use Baoziyoo\Hyperf\Example\Enum\StatusEnum;

class DemoQuery extends PageQuery
{
    #[ApiModelProperty('状态')]
    // #[Required]
    public StatusEnum $statusEnum;

    #[ApiModelProperty('测试', 'bb')]
    public string $test = 'tt';

    #[ApiModelProperty('测试2')]
    public ?bool $isNew;

    #[ApiModelProperty('名称')]
    #[max(5)]
    #[In(['qq', 'aa'])]
    public string $name;

    #[ApiModelProperty('邮箱')]
    #[Str]
    #[Regex('/^.+@.+$/i')]
    #[StartsWith('aa,bb')]
    #[max(10, '超长啦....')]
    public string $email;

    #[ApiModelProperty('数量')]
    #[Integer]
    #[Between(1, 5)]
    #[Required]
    private int $num;

    public function getNum(): int
    {
        return $this->num;
    }

    public function setNum(int $num): void
    {
        $this->num = $num;
    }
}
