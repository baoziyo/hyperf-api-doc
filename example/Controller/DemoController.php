<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-09 10:21:11
 * ChangeTime: 2023-04-26 10:21:30
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\Example\Controller;

use App\Model\Activity;
use Baoziyoo\Hyperf\ApiDocs\Annotation\Api;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiFormData;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiHeader;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiOperation;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiResponse;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiSecurity;
use Hyperf\Database\Model\Relations\HasOne;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestBody;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestFormData;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestHeader;
use Baoziyoo\Hyperf\DTO\Annotation\Contracts\RequestQuery;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Baoziyoo\Hyperf\Example\DTO\Address;
use Baoziyoo\Hyperf\Example\DTO\Header\DemoToken;
use Baoziyoo\Hyperf\Example\DTO\PageQuery;
use Baoziyoo\Hyperf\Example\DTO\Request\DemoBodyRequest;
use Baoziyoo\Hyperf\Example\DTO\Request\DemoFormData;
use Baoziyoo\Hyperf\Example\DTO\Request\DemoQuery;
use Baoziyoo\Hyperf\Example\DTO\Response\ActivityPage;
use Baoziyoo\Hyperf\Example\DTO\Response\ActivityResponse;
use Baoziyoo\Hyperf\Example\DTO\Response\Contact;
use JetBrains\PhpStorm\Deprecated;

#[Controller(prefix: '/exampleDemo')]
#[Api(tags: 'demo管理', position: 1)]
#[ApiHeader('apiHeader')]
class DemoController
{
    public function __construct(protected RequestInterface $request)
    {
    }

    #[ApiOperation('1:查询')]
    #[PostMapping(path: 'query')]
    public function query(#[RequestBody] #[Valid] DemoQuery $request): Contact
    {
        dump($request);
        return new Contact();
    }

    #[ApiOperation('2:提交body数据和get参数')]
    #[PutMapping(path: 'add')]
    public function add(#[RequestBody] #[Valid] DemoBodyRequest $request, #[RequestQuery] DemoQuery $query): ActivityResponse
    {
        dump($request);
        dump($query);
        return new ActivityResponse();
    }

    #[ApiOperation('3:表单提交')]
    #[PostMapping(path: 'fromData')]
    #[ApiFormData(name: 'photo', format: 'binary')]
    #[ApiResponse(response: '200', description: 'success', type: Address::class, isArray: true)]
    public function fromData(#[RequestFormData] DemoFormData $formData): array
    {
        $file = $this->request->file('photo');
        dump($file);
        var_dump($formData);
        return [new Address()];
    }

    #[ApiOperation('4:查询单体记录')]
    #[GetMapping(path: 'find/{id}/and/{in}')]
    #[ApiHeader('test2')]
    public function find(int $id, float $in): array
    {
        return ['$id' => $id, '$in' => $in];
    }

    #[ApiOperation('5:分页')]
    #[GetMapping(path: 'page')]
    public function page(#[RequestQuery] PageQuery $pageQuery): ActivityPage
    {
        $model = Activity::with(['activityUser' => function ($query) {
            /* @var HasOne $query */
            $query->orderBy('id', 'desc')->with(['case']);
        }])
//            ->where('id','>',90)
            ->paginate($pageQuery->getSize());
        return ActivityPage::from($model);
    }

    #[ApiOperation('6:更新')]
    #[PutMapping(path: 'update/{id}')]
    public function update(int $id): int
    {
        return $id;
    }

    #[ApiOperation('7:删除')]
    #[DeleteMapping(path: 'delete/{id}')]
    public function delete(int $id): int
    {
        return $id;
    }

    #[ApiOperation('patch方法')]
    #[PatchMapping(path: 'patch/{id}')]
    #[Deprecated]
    public function patch(int $id): int
    {
        return $id;
    }

    #[ApiOperation('获取请求头')]
    #[PostMapping(path: 'getHeader/{id}')]
    #[ApiSecurity]
    public function getHeader(#[RequestHeader] #[Valid] DemoToken $header): DemoToken
    {
        dump($header);
        return $header;
    }

    #[ApiOperation('返回 obj(弃用)')]
    #[GetMapping(path: 'obj')]
    #[Deprecated]
    public function obj(): object
    {
        return new Address();
    }

    #[ApiOperation('登录', security: false)]
    #[PostMapping(path: 'login')]
    public function login(#[RequestBody] #[Valid] DemoBodyRequest $request): DemoBodyRequest
    {
//         dump($request);
        return $request;
    }
}
