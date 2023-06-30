<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-02-07 04:16:23
 * ChangeTime: 2023-04-26 10:21:29
 */

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\ApiDocs\Annotation\Api;
use Baoziyoo\Hyperf\ApiDocs\Exception\ApiDocsException;
use Baoziyoo\Hyperf\ApiDocs\Listener\BootAppRouteListener;
use Hyperf\Engine\Constant;
use Hyperf\HttpMessage\Stream\SwooleFileStream;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Swow\Psr7\Message\BufferStream;

#[Api(hidden: true)]
class SwaggerController
{
    private string $swaggerUiPath = BASE_PATH . '/vendor/swagger-api/swagger-ui/dist';

    private string $outputDir;

    private array $uiFileList;

    private array $swaggerFileList;

    public function __construct(private SwaggerConfig $swaggerConfig, private ResponseInterface $response)
    {
        $this->outputDir = $this->swaggerConfig->getOutputDir();
        $this->uiFileList = scandir($this->swaggerUiPath);
        $this->swaggerFileList = scandir($this->outputDir);
    }

    public function index(): PsrResponseInterface
    {
        $filePath = BASE_PATH . '/vendor/baoziyoo/hyperf-api-doc/src/web/index.html';
        $contents = file_get_contents($filePath);
        $contents = str_replace(['{{$path}}', '{{$url}}'], ['.' . $this->swaggerConfig->getPrefixUrl(), '.' . $this->getSwaggerFileUrl(BootAppRouteListener::$httpServerName)], $contents);
        return $this->response->withAddedHeader('content-type', 'text/html')->withBody(new SwooleStream($contents));
    }

    public function getFile(string $file): PsrResponseInterface
    {
        if (!in_array($file, $this->uiFileList)) {
            throw new ApiDocsException('File does not exist');
        }
        $file = $this->swaggerUiPath . '/' . $file;
        return $this->fileResponse($file);
    }

    public function getJsonFile(string $httpName): PsrResponseInterface
    {
        $file = $httpName . '.json';
        if (!in_array($file, $this->swaggerFileList)) {
            throw new ApiDocsException('File does not exist');
        }
        $filePath = $this->outputDir . '/' . $file;
        return $this->fileResponse($filePath);
    }

    public function getYamlFile(string $httpName): PsrResponseInterface
    {
        $file = $httpName . '.yaml';
        if (!in_array($file, $this->swaggerFileList)) {
            throw new ApiDocsException('File does not exist');
        }
        $filePath = $this->outputDir . '/' . $file;
        return $this->fileResponse($filePath);
    }

    protected function fileResponse(string $filePath)
    {
        if (Constant::ENGINE === 'Swoole') {
            $stream = new SwooleFileStream($filePath);
        } elseif (Constant::ENGINE === 'Swow') {
            $stream = new BufferStream(file_get_contents($filePath));
        } else {
            $stream = new SwooleStream(file_get_contents($filePath));
        }
        $response = $this->response->withBody($stream);

        switch (pathinfo($filePath)['extension'] ?? '') {
            case 'js':
            case 'map':
                $response = $response->withAddedHeader('content-type', 'application/javascript')->withAddedHeader('cache-control', 'max-age=43200');
                break;
            case 'css':
                $response = $response->withAddedHeader('content-type', 'text/css')->withAddedHeader('cache-control', 'max-age=43200');
                break;
        }

        return $response;
    }

    private function getSwaggerFileUrl($serverName): string
    {
        return $this->swaggerConfig->getPrefixUrl() . '/' . $serverName . '.' . $this->swaggerConfig->getFormat();
    }
}
