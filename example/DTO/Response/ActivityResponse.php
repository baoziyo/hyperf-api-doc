<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Response;

use App\Model\Activity;;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\Mapper;
use Baoziyoo\Hyperf\Example\DTO\ActivityUser;

class ActivityResponse
{
    #[ApiModelProperty('id')]
    public string $id;

    #[ApiModelProperty('活动名称')]
    public string $activityName;

    /**
     * @var ActivityUser[]
     */
    public array $activityUser;

    public static function from(?Activity $obj): ?ActivityResponse
    {
        return Mapper::copyProperties($obj, new self());
    }
}
