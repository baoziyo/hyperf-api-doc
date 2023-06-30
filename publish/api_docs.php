<?php
/*
 * Copyright (c) 2023. ogg. Inc. All Rights Reserved.
 * ogg sit down and start building bugs in sunny weather.
 * Author: Ogg <baoziyoo@gmail.com>.
 * LastChangeTime: 2023-01-17 02:29:20
 * ChangeTime: 2023-04-26 10:21:30
 */

declare(strict_types=1);

return [
    // enable false 将不会启动 swagger 服务
    'enable' => env('APP_ENV') !== 'prod',
    'format' => 'json',
    'output_dir' => BASE_PATH . '/runtime/swagger',
    'prefix_url' => env('API_DOCS_PREFIX_URL', '/swagger'),
    // 全局responses
    'responses' => [
        ['response' => 401, 'description' => 'Unauthorized'],
        ['response' => 500, 'description' => 'System error'],
    ],
    // swagger 的基础配置  会映射到OpenAPI对象
    'swagger' => [
        'info' => [
            'title' => 'API DOC',
            'version' => '0.1',
            'description' => 'swagger api desc',
        ],
        'servers' => [
            [
                'url' => 'http://127.0.0.1:9501',
                'description' => 'OpenApi host',
            ],
        ],
        'components' => [
            'securitySchemes' => [
                [
                    'securityScheme' => 'Authorization',
                    'type' => 'apiKey',
                    'in' => 'header',
                    'name' => 'Authorization',
                ],
            ],
        ],
        'security' => [
            ['Authorization' => []],
        ],
        'externalDocs' => [
            'description' => 'Find out more about Swagger',
            'url' => 'https://github.com/tw2066/api-docs',
        ],
    ],
];
