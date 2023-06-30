<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-02-07 04:16:22
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiFormData;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiHeader;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\ApiDocs\Annotation\BaseParam;
use Baoziyoo\Hyperf\DTO\ApiAnnotation;
use Baoziyoo\Hyperf\DTO\Scan\MethodParametersManager;
use Baoziyoo\Hyperf\DTO\Scan\PropertyManager;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\In;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\Required;
use Hyperf\Di\MethodDefinitionCollectorInterface;
use Hyperf\Di\ReflectionManager;
use Hyperf\Utils\Arr;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Throwable;

class GenerateParameters
{
    /**
     * @param ApiHeader[] $apiHeaderArr
     * @param ApiFormData[] $apiFormDataArr
     */
    public function __construct(
        private readonly string $controller,
        private readonly string $action,
        private readonly array $apiHeaderArr,
        private readonly array $apiFormDataArr,
        private readonly ContainerInterface $container,
        private readonly MethodDefinitionCollectorInterface $methodDefinitionCollector,
        private readonly SwaggerComponents $swaggerComponents,
        private readonly SwaggerCommon $common
    )
    {
    }

    public function generate(): array
    {
        $result = [
            'requestBody' => [],
        ];
        // FormData类名
        $requestFormDataclass = '';
        $parameterArr = $this->getParameterArrByBaseParam($this->apiHeaderArr);
        $definitions = $this->methodDefinitionCollector->getParameters($this->controller, $this->action);

        foreach ($definitions as $definition) {
            // query path
            $parameterClassName = $definition->getName();
            $paramName = $definition->getMeta('name');
            // 判断是否为简单类型
            $simpleSwaggerType = $this->common->getSimpleType2SwaggerType($parameterClassName);
            if ($simpleSwaggerType !== null) {
                $parameter = new OA\Parameter();
                $parameter->required = true;
                $parameter->name = $paramName;
                $parameter->in = 'path';
                $schema = new OA\Schema();
                $schema->type = $simpleSwaggerType;
                $parameter->schema = $schema;
                $parameterArr[] = $parameter;
                continue;
            }

            if ($this->container->has($parameterClassName)) {
                $methodParameter = MethodParametersManager::getMethodParameter($this->controller, $this->action, $paramName);
                if ($methodParameter === null) {
                    continue;
                }

                if ($methodParameter->isRequestBody()) {
                    $requestBody = new OA\RequestBody();
                    $requestBody->required = true;
                    // $requestBody->description = '';
                    $requestBody->content = $this->getContent($parameterClassName);
                    $result['requestBody'] = $requestBody;
                }
                if ($methodParameter->isRequestQuery()) {
                    $parameterArr += $this->getParameterArrByClass($parameterClassName, 'query');
                }
                if ($methodParameter->isRequestHeader()) {
                    $parameterArr += $this->getParameterArrByClass($parameterClassName, 'header');
                }
                if ($methodParameter->isRequestFormData()) {
                    $requestFormDataclass = $parameterClassName;
                }
            }
        }
        // Form表单
        if (!empty($requestFormDataclass) || !empty($this->apiFormDataArr)) {
            $requestBody = new OA\RequestBody();
            $requestBody->required = true;
            // $requestBody->description = '';
            $mediaType = new OA\MediaType();
            $mediaType->mediaType = 'multipart/form-data';
            // $parameterClassName
            $mediaType->schema = $this->generateFormDataSchemas($requestFormDataclass, $this->apiFormDataArr);
            $mediaType->schema->type = 'object';
            $requestBody->content = [];
            $requestBody->content[$mediaType->mediaType] = $mediaType;
            $result['requestBody'] = $requestBody;
        }

        $result['parameter'] = $parameterArr;
        return $result;
    }

    public function generateFormDataSchemas($className, $apiFormDataArr): OA\Schema
    {
        $schema = new OA\Schema();
        $data = $this->swaggerComponents->getProperties($className);
        $annotationData = $this->getPropertiesByBaseParam($apiFormDataArr);
        $schema->properties = Arr::merge($data['propertyArr'], $annotationData['propertyArr']);
        $schema->required = Arr::merge($data['requiredArr'], $annotationData['requiredArr']);
        return $schema;
    }

    public function getParameterArrByClass(string $parameterClassName, string $in): array
    {
        $parameters = [];
        $rc = ReflectionManager::reflectClass($parameterClassName);
        foreach ($rc->getProperties() ?? [] as $reflectionProperty) {
            $parameter = new OA\Parameter();
            $schema = new OA\Schema();
            $parameter->name = $reflectionProperty->getName();
            $parameter->in = $in;
            try {
                $schema->default = $reflectionProperty->getValue(make($parameterClassName));
            } catch (Throwable) {
            }
            $phpType = $this->common->getTypeName($reflectionProperty);
            $enum = PropertyManager::getProperty($phpType, $reflectionProperty->name)?->enum;
            if ($enum) {
                $phpType = $enum->backedType;
            }
            $schema->type = $this->common->getSwaggerType($phpType);

            $apiModelProperty = ApiAnnotation::getProperty($parameterClassName, $reflectionProperty->getName(), ApiModelProperty::class);
            $apiModelProperty = $apiModelProperty ?: new ApiModelProperty();
            if ($apiModelProperty->hidden) {
                continue;
            }
            $requiredAnnotation = ApiAnnotation::getProperty($parameterClassName, $reflectionProperty->getName(), Required::class);
            /** @var In $inAnnotation */
            $inAnnotation = ApiAnnotation::getProperty($parameterClassName, $reflectionProperty->getName(), In::class);
            if ($inAnnotation !== null) {
                $schema->enum = $inAnnotation->getValue();
            }
            if ($enum !== null) {
                $schema->enum = $enum->valueList;
            }
            if ($apiModelProperty->required !== null) {
                $parameter->required = $apiModelProperty->required;
            }
            if ($requiredAnnotation !== null) {
                $parameter->required = true;
            }
            $parameter->description = $apiModelProperty->value ?? '';
            $parameters[] = $parameter;
        }
        return $parameters;
    }

    protected function getContent(string $className, string $mediaTypeStr = 'application/json'): array
    {
        $arr = [];
        $mediaType = new OA\MediaType();
        $mediaType->mediaType = $mediaTypeStr;
        $mediaType->schema = $this->getJsonContent($className);
        $arr[] = $mediaType;
        return $arr;
    }

    protected function getJsonContent(string $className): OA\JsonContent
    {
        $jsonContent = new OA\JsonContent();
        $this->swaggerComponents->generateSchemas($className);
        $jsonContent->ref = $this->common->getComponentsName($className);
        return $jsonContent;
    }

    /**
     * @param BaseParam[] $baseParam
     */
    private function getParameterArrByBaseParam(array $baseParam): array
    {
        $parameters = [];
        foreach ($baseParam ?? [] as $param) {
            if ($param->hidden) {
                continue;
            }
            $parameter = new OA\Parameter();
            $schema = new OA\Schema();
            $parameter->name = $param->name;
            $parameter->in = $param->getIn();
            $schema->default = $param->default;
            $schema->type = $this->common->getSwaggerType($param->type);
            // 描述
            $parameter->description = $param->description;
            if ($param->required !== null) {
                $parameter->required = $param->required;
            }
            $schema->default = $param->default;
            $schema->format = $param->format;
            $parameters[] = $parameter;
        }
        return $parameters;
    }

    /**
     * @param BaseParam[] $baseParam
     */
    private function getPropertiesByBaseParam(array $baseParam): array
    {
        $propertyArr = [];
        $requiredArr = [];

        foreach ($baseParam ?? [] as $param) {
            if ($param->hidden) {
                continue;
            }
            // 属性
            $property = new OA\Property();
            // 字段名称
            $fieldName = $param->name;
            $property->property = $fieldName;
            // 描述
            $property->description = $param->description;
            $param->required && $requiredArr[] = $fieldName;
            $property->default = $param->default;
            $property->type = $this->common->getSwaggerType($param->type);
            $property->format = $param->format;
            $propertyArr[] = $property;
        }
        return ['propertyArr' => $propertyArr, 'requiredArr' => $requiredArr];
    }
}
