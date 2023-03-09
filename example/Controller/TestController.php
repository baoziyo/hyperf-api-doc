<?php

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
