<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:15:59
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\DTO\Response;

use Hyperf\Contract\LengthAwarePaginatorInterface;

class ActivityPage extends Page
{
    /**
     * @var ActivityResponse[]
     */
    public array $content;

    public static function from(LengthAwarePaginatorInterface $page): ActivityPage
    {
        $activityPage = new self();
        $arr = [];
        foreach ($page as $model) {
            $arr[] = ActivityResponse::from($model);
        }
        $activityPage->content = $arr;
        $activityPage->setTotal($page->total());
        $activityPage->setCurrentPage($page->currentPage());
        $activityPage->setPerPage($page->perPage());
        return $activityPage;
    }
}
