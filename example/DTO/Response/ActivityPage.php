<?php

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
