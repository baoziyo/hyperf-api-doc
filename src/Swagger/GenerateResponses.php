<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-01-17 02:54:36
 * ChangeTime: 2023-04-26 10:21:28
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiResponse;
use Hyperf\Di\MethodDefinitionCollectorInterface;
use Hyperf\Utils\Arr;
use OpenApi\Annotations\Schema;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;

class GenerateResponses
{
    public function __construct(
        private readonly string                             $className,
        private readonly string                             $methodName,
        private readonly array                              $apiResponseArr,
        private readonly SwaggerConfig                      $swaggerConfig,
        private readonly MethodDefinitionCollectorInterface $methodDefinitionCollector,
        private readonly ContainerInterface                 $container,
        private readonly SwaggerComponents                  $swaggerComponents,
        private readonly SwaggerCommon                      $common,
    )
    {
    }

    /**
     * 生成Response.
     */
    public function generate(): array
    {
        $definition = $this->methodDefinitionCollector->getReturnType($this->className, $this->methodName);
        $returnTypeClassName = $definition->getName();
        // 全局
        $globalResp = $this->getGlobalResp();
        // 注解
        $annotationResp = $this->getAnnotationResp();
        $arr = [];

        $code = $this->swaggerConfig->getResponsesCode();
        $response = new OA\Response();
        $response->response = $code;
        $response->description = 'successful operation';
        $content = $this->getContent($returnTypeClassName);
        $response->content = $content;
        $arr[$code] = $response;

        $annotationResp && $arr = Arr::merge($arr, $annotationResp);
        $globalResp && $arr = Arr::merge($arr, $globalResp);

        return array_values($arr);
    }

    protected function getContent(string $returnTypeClassName, bool $isArray = false, string $mode = 'simple'): array
    {
        $arr = [];
        $mediaType = new OA\MediaType();

        $mediaTypeStr = 'text/plain';
        $parentSchema = new OA\Schema();
        $parentSchema->type = 'object';
        $parentSchema->properties = [
            new OA\Property(property: 'code', type: 'int', default: 200),
            new OA\Property(property: 'message', type: 'string', default: '操作成功.'),
            new OA\Property(property: 'time', type: 'int', default: time()),
        ];
        $mediaTypeStr = 'application/json';

        // 简单类型
        if ($this->common->isSimpleType($returnTypeClassName)) {
            $schema = new OA\Schema();
            $schema->type = $this->common->getSwaggerType($returnTypeClassName);
            // 数组
            if ($isArray) {
                $mediaTypeStr = 'application/json';
                $schema->type = 'array';
                $items = new OA\Items();
                $items->type = $this->common->getSwaggerType($returnTypeClassName);
                $schema->items = $items;
            }
            $parentSchema->properties[] = new OA\Property(property: 'data', schema: $schema);
            $mediaType->schema = $parentSchema;
        } elseif ($this->container->has($returnTypeClassName)) {
            $mediaTypeStr = 'application/json';

            $this->swaggerComponents->generateSchemas($returnTypeClassName);
            if ($isArray) {
                $items = new OA\Items();
                $items->ref = $this->common->getComponentsName($returnTypeClassName);
                if ($mode === 'complex') {
                    $parentSchema->properties[] = new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'list', items: $items),
                        new OA\Property(property: 'count', type: 'int', default: 1),
                    ], type: 'object');
                } else {
                    $parentSchema->properties[] = new OA\Property(property: 'data', items: $items);
                }
            } else {
                $parentSchema->properties[] = new OA\Property(property: 'data', ref: $this->common->getComponentsName($returnTypeClassName));
            }
            $mediaType->schema = $parentSchema;
        } else {
            // 其他类型数据 eg:mixed
            return [];
        }

        $arr[$mediaTypeStr] = $mediaType;
        $mediaType->mediaType = $mediaTypeStr;
        return $arr;
    }

    /**
     * 获取返回类型的JsonContent.
     */
    protected function getJsonContent(string $returnTypeClassName, bool $isArray, string $mode = 'simple'): OA\JsonContent
    {
        $jsonContent = new OA\JsonContent();
        $this->swaggerComponents->generateSchemas($returnTypeClassName);

        if ($isArray) {
            $jsonContent->type = 'array';
            $items = new OA\Items();
            $items->ref = $this->common->getComponentsName($returnTypeClassName);
            $jsonContent->items = $items;
        } else {
            $jsonContent->ref = $this->common->getComponentsName($returnTypeClassName);
        }

        return $jsonContent;
    }

    /**
     * 获得全局Response.
     */
    protected function getGlobalResp(): array
    {
        $resp = [];
        foreach ($this->swaggerConfig->getResponses() as $value) {
            $apiResponse = new ApiResponse();
            $apiResponse->type = $value['type'] ?? null;
            $apiResponse->response = $value['response'] ?? null;
            $apiResponse->description = $value['description'] ?? null;
            $apiResponse->isArray = $value['isArray'] ?? true;

            $resp[$apiResponse->response] = $this->getOAResp($apiResponse);
        }
        return $resp;
    }

    protected function getOAResp(ApiResponse $apiResponse): OA\Response
    {
        $response = new OA\Response();
        $response->response = $apiResponse->response;
        $response->description = $apiResponse->description;
        if (!empty($apiResponse->type)) {
            $content = $this->getContent($apiResponse->type, $apiResponse->isArray, $apiResponse->mode);
            $content && $response->content = $content;
        }
        return $response;
    }

    /**
     * 获取注解上的Response.
     * @return OA\Response[]
     */
    protected function getAnnotationResp(): array
    {
        $resp = [];
        /** @var ApiResponse $apiResponse */
        foreach ($this->apiResponseArr as $apiResponse) {
            $resp[$apiResponse->response] = $this->getOAResp($apiResponse);
        }
        return $resp;
    }
}
