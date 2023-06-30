<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:19:33
 * ChangeTime: 2023-04-26 10:21:30
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\Controller;

use Baoziyoo\Hyperf\ApiDocs\Annotation\Api;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiHeader;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiOperation;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestBody;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Baoziyoo\Hyperf\Example\DTO\Request\DemoQuery;
use Baoziyoo\Hyperf\Example\DTO\Response\Contact;

#[Controller(prefix: '/exampleTest')]
#[Api(tags: '测试管理控制器', position: 2)]
#[ApiHeader('testHeader')]
class TestController
{
    #[ApiOperation('查询', security: false)]
    #[PostMapping(path: 'query')]
    public function query(#[RequestBody] #[Valid] DemoQuery $request): Contact
    {
        var_dump($request);
        return new Contact();
    }
}
