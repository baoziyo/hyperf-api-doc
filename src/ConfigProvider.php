<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs;

use Baoziyoo\Hyperf\ApiDocs\Listener\AfterDtoStartListener;
use Baoziyoo\Hyperf\ApiDocs\Listener\AfterWorkerStartListener;
use Baoziyoo\Hyperf\ApiDocs\Listener\BootAppRouteListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'listeners' => [
                AfterDtoStartListener::class,
                BootAppRouteListener::class,
                AfterWorkerStartListener::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for api-docs.',
                    'source' => __DIR__ . '/../publish/api_docs.php',
                    'destination' => BASE_PATH . '/config/autoload/api_docs.php',
                ],
            ],
        ];
    }
}
