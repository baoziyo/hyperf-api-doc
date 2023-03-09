<?php

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
