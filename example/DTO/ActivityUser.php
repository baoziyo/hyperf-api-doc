<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 02:10:24
 * ChangeTime: 2023-04-26 10:21:30
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO;

use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestBody;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestQuery;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestFormData;


class ActivityUser
{
    public string $id;

    public string $activityId;

    public string $activityName;

    public ?CaseData $case;

    public int $caseCount = 0;

    public function setCase(?CaseData $case): void
    {
        $this->case = $case;
    }
}
