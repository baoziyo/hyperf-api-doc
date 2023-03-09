<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Swagger;

use Baoziyoo\Hyperf\DTO\Mapper;
use Hyperf\Contract\ConfigInterface;

class SwaggerConfig
{
    private bool $enable = false;

    private string $output_dir = '';

    private string $prefix_url = '';

    private array $responses = [];

    private array $swagger = [];

    private string $responses_code = '200';

    private string $format = 'json';

    public function __construct(ConfigInterface $config)
    {
        $data = $config->get('api_docs', []);
        $jsonMapper = Mapper::getJsonMapper('bIgnoreVisibility');
        // 私有属性和函数
        $jsonMapper->bIgnoreVisibility = true;
        $jsonMapper->map($data, $this);
    }

    public function setPrefixUrl(string $prefix_url): void
    {
        $this->prefix_url = '/' . trim($prefix_url, '/');
    }

    public function isEnable(): bool
    {
        return $this->enable;
    }

    public function getOutputDir(): string
    {
        return $this->output_dir;
    }

    public function getPrefixUrl(): string
    {
        return $this->prefix_url;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @return array [
     *               'info' => [],
     *               'servers' => [],
     *               'externalDocs' => [],
     *               'components' => [
     *               'securitySchemes'=>[]
     *               ],
     *               'openapi'=>'',
     *               'security'=>[],
     *               ]
     */
    public function getSwagger(): array
    {
        return $this->swagger;
    }

    public function getResponsesCode(): string
    {
        return $this->responses_code;
    }

    public function getFormat(): string
    {
        return $this->format === 'json' ? 'json' : 'yaml';
    }
}
