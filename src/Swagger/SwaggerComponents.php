<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-02-07 04:16:23
 * ChangeTime: 2023-04-26 10:21:28
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModel;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Baoziyoo\Hyperf\DTO\ApiAnnotation;
use Baoziyoo\Hyperf\DTO\Scan\PropertyManager;
use Baoziyoo\Hyperf\DTO\Validation\Annotation\Rule\In;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\ReflectionManager;
use OpenApi\Attributes as OA;
use Psr\Http\Message\UploadedFileInterface;

class SwaggerComponents
{
    protected static array $schemas = [];

    public function __construct(private readonly SwaggerCommon $common)
    {
    }

    public function getSchemas(): array
    {
        return self::$schemas;
    }

    public function setSchemas(array $schemas): void
    {
        self::$schemas = $schemas;
    }

    public function getProperties(string $className): array
    {
        if (empty($className)) {
            return ['propertyArr' => [], 'requiredArr' => []];
        }

        $obj = make($className);
        $rc = ReflectionManager::reflectClass($className);
        $propertyArr = [];
        $requiredArr = [];
        // 循环类中字段
        foreach ($rc->getProperties() as $reflectionProperty) {
            // 属性
            $property = new OA\Property();

            $fieldName = $reflectionProperty->getName();

            $apiModelProperty = ApiAnnotation::getProperty($className, $fieldName, ApiModelProperty::class);
            $apiModelProperty = $apiModelProperty ?: new ApiModelProperty();
            /** @var In $inAnnotation */
            $inAnnotation = ApiAnnotation::getProperty($className, $reflectionProperty->getName(), In::class);

            if ($apiModelProperty->hidden) {
                continue;
            }
            // 字段名称
            $property->property = $fieldName;

            // 描述
            $apiModelProperty->value !== null && $property->description = $apiModelProperty->value;
            if ($apiModelProperty->required !== null) {
                $apiModelProperty->required && $requiredArr[] = $fieldName;
            }
            $property->example = $apiModelProperty->example;
            if ($reflectionProperty->isPublic() && $reflectionProperty->isInitialized($obj)) {
                $property->default = $reflectionProperty->getValue($obj);
            }

            $propertyClass = PropertyManager::getProperty($className, $fieldName);
            // swagger 类型
            $swaggerType = $this->common->getSwaggerType($propertyClass->phpSimpleType);
            // 简单类型
            if ($propertyClass->isSimpleType) {
                // 数组
                if ($swaggerType == 'array') {
                    $property->type = 'array';
                    $items = new OA\Items();
                    // $items->type = 'string';  可选
                    $property->items = $items;
                } else {
                    $property->type = $swaggerType;
                }
            } // 枚举:in
            elseif (!empty($inAnnotation)) {
                $property->type = $swaggerType;
                $property->enum = $inAnnotation->getValue();
            } // 枚举类型
            elseif ($propertyClass->enum) {
                $property->type = $this->common->getSwaggerType($propertyClass->enum->backedType);
                $property->enum = $propertyClass->enum->valueList;
            } // 普通类
            else {
                if ($swaggerType == 'array') {
                    $property->type = 'array';
                    if (!empty($propertyClass->arrClassName)) {
                        $items = new OA\Items();
                        $items->ref = $this->common->getComponentsName($propertyClass->arrClassName);
                        $property->items = $items;

                        $this->generateSchemas($propertyClass->arrClassName);
                    }
                    if (!empty($propertyClass->arrSimpleType)) {
                        $items = new OA\Items();
                        $items->type = $this->common->getSwaggerType($propertyClass->arrSimpleType);
                        $property->items = $items;
                    }
                } else {
                    $property->ref = $this->common->getComponentsName($propertyClass->className);
                    $this->generateSchemas($propertyClass->className);
                }
            }
            $propertyArr[] = $property;
        }
        return ['propertyArr' => $propertyArr, 'requiredArr' => $requiredArr];
    }

    public function generateSchemas(string $className)
    {
        $simpleClassName = $this->common->getSimpleClassName($className);
        if (isset(static::$schemas[$simpleClassName])) {
            return static::$schemas[$simpleClassName];
        }
        $schema = new OA\Schema();
        $schema->schema = $simpleClassName;

        $data = $this->getProperties($className);
        $schema->properties = $data['propertyArr'];
        /** @var ApiModel $apiModel */
        $apiModel = AnnotationCollector::getClassAnnotation($className, ApiModel::class);
        if ($apiModel) {
            $schema->description = $apiModel->value;
        }
        $data['requiredArr'] && $schema->required = $data['requiredArr'];
        self::$schemas[$simpleClassName] = $schema;
        return self::$schemas[$simpleClassName];
    }
}
