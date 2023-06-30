<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-03-02 11:11:54
 * ChangeTime: 2023-04-26 10:21:28
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\ApiDocs\Exception\ApiDocsException;
use Baoziyoo\Hyperf\DTO\Mapper;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\Tag;
use SplPriorityQueue;

class SwaggerOpenApi
{
    public ?SplPriorityQueue $queueTags;

    private ?OpenApi $openApi = null;

    private ?SplPriorityQueue $queuePaths;

    private array $tags = [];

    private array $componentsSchemas = [];

    public function __construct(private readonly SwaggerConfig $swaggerConfig)
    {
    }

    public function init(): void
    {
        $this->openApi = new OpenApi();
        $this->openApi->paths = [];
        $this->openApi->tags = [];
        $this->openApi->components = new OA\Components();
        $this->tags = [];
        $this->queuePaths = new SplPriorityQueue();
        $this->queueTags = new SplPriorityQueue();
        $this->setOpenapiVersion();
        $this->setInfo();
        $this->setServers();
        $this->setComponentsSecuritySchemes();
        $this->setSecurity();
        $this->setExternalDocs();
    }

    public function clean(): void
    {
        $this->openApi = null;
        $this->tags = [];
        $this->queuePaths = null;
        $this->queueTags = null;
    }

    public function getQueuePaths(): SplPriorityQueue
    {
        return $this->queuePaths;
    }

    /**
     * 设置OpenapiVersion.
     */
    public function setOpenapiVersion(): void
    {
        if (!empty($this->swaggerConfig->getSwagger()['openapi'])) {
            $this->openApi->openapi = $this->swaggerConfig->getSwagger()['openapi'];
        }
    }

    /**
     * 设置openApi对象 security.
     */
    public function setSecurity(): void
    {
        if (!empty($this->swaggerConfig->getSwagger()['security'])) {
            $this->openApi->security = $this->swaggerConfig->getSwagger()['security'];
        }
    }

    /**
     * 设置tags.
     */
    public function setTags(string $tagName, int $position, Tag $tag): void
    {
        if (isset($this->tags[$tagName])) {
            return;
        }
        $this->tags[$tagName] = true;
        $this->queueTags->insert($tag, $position);
    }

    public function setComponentsSchemas(array $componentsSchemas): void
    {
        $this->componentsSchemas = array_values($componentsSchemas);
    }

    public function setComponentsSecuritySchemes(): void
    {
        $securitySchemes = $this->swaggerConfig->getSwagger()['components']['securitySchemes'] ?? [];
        if ($securitySchemes) {
            $this->openApi->components->securitySchemes = [];
            foreach ($securitySchemes as $securityScheme) {
                $this->openApi->components->securitySchemes[] = Mapper::map($securityScheme, new OA\SecurityScheme());
            }
        }
    }

    public function save(string $serverName): void
    {
        // 设置paths
        while (!$this->queuePaths->isEmpty()) {
            $this->openApi->paths[] = $this->queuePaths->extract();
        }
        // 设置tags
        while (!$this->queueTags->isEmpty()) {
            $this->openApi->tags[] = $this->queueTags->extract();
        }
        // 设置components->schemas
        $this->openApi->components->schemas = array_values($this->componentsSchemas);
        // 创建目录
        $outputDir = $this->swaggerConfig->getOutputDir();
        if (file_exists($outputDir) === false) {
            if (mkdir($outputDir, 0755, true) === false) {
                throw new ApiDocsException("Failed to create a directory : {$outputDir}");
            }
        }
        $outputFile = $outputDir . '/' . $serverName . '.' . $this->swaggerConfig->getFormat();
        $this->openApi->saveAs($outputFile);
    }

    private function setInfo(): void
    {
        $info = $this->swaggerConfig->getSwagger()['info'] ?? [];
        $this->openApi->info = Mapper::map($info, new OA\Info());
    }

    private function setExternalDocs(): void
    {
        $externalDocs = $this->swaggerConfig->getSwagger()['externalDocs'] ?? [];
        if ($externalDocs) {
            $this->openApi->externalDocs = Mapper::map($externalDocs, new OA\ExternalDocumentation());
        }
    }

    private function setServers(): void
    {
        $servers = $this->swaggerConfig->getSwagger()['servers'] ?? [];
        if ($servers) {
            $this->openApi->servers = Mapper::mapArray($servers, OA\Server::class);
        }
    }
}
