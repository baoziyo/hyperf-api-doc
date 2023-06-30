<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-02-28 04:03:24
 * ChangeTime: 2023-04-26 10:21:29
 */

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
