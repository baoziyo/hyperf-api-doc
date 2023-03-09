<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Listener;

use Baoziyoo\Hyperf\ApiDocs\Swagger\SwaggerComponents;
use Baoziyoo\Hyperf\ApiDocs\Swagger\SwaggerConfig;
use Baoziyoo\Hyperf\ApiDocs\Swagger\SwaggerOpenApi;
use Baoziyoo\Hyperf\ApiDocs\Swagger\SwaggerPaths;
use Baoziyoo\Hyperf\DTO\Event\AfterDtoStart;
use Closure;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpServer\Router\Handler;
use OpenApi\Attributes\PathItem;
use RuntimeException;

class AfterDtoStartListener implements ListenerInterface
{
    public function __construct(
        private readonly StdoutLoggerInterface $logger,
        private readonly SwaggerOpenApi        $swaggerOpenApi,
        private readonly SwaggerComponents     $swaggerComponents,
        private readonly SwaggerConfig         $swaggerConfig,
    )
    {
    }

    public function listen(): array
    {
        return [
            AfterDtoStart::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof AfterDtoStart) {
            $server = $event->serverConfig;
            $router = $event->router;

            if (!$this->swaggerConfig->isEnable()) {
                return;
            }
            if (!$this->swaggerConfig->getOutputDir()) {
                return;
            }

            $this->swaggerOpenApi->init();

            /** @var SwaggerPaths $swagger */
            $swagger = make(SwaggerPaths::class, [$server['name']]);

            $newRouteData = [];
            foreach ($router->getData() ?? [] as $routeData) {
                foreach ($routeData ?? [] as $methods => $handlerArr) {
                    array_walk_recursive($handlerArr, static function ($item) use (&$newRouteData, $methods) {
                        if ($item instanceof Handler && !($item->callback instanceof Closure)) {
                            $newRouteData[$item->route][] = ['item' => $item, 'methods' => $methods];
                        }
                    });
                }
            }

            foreach ($newRouteData as $route) {
                $pathItem = new PathItem();
                foreach ($route as $item) {
                    $prepareHandler = $this->prepareHandler($item['item']->callback);
                    if (count($prepareHandler) > 1) {
                        [$controller, $methodName] = $prepareHandler;
                        $swagger->addPath($controller, $methodName, $item['item']->route, $item['methods'], $pathItem);
                    }
                }
            }


            $schemas = $this->swaggerComponents->getSchemas();
            $this->swaggerOpenApi->setComponentsSchemas($schemas);
            $this->swaggerOpenApi->save($server['name']);

            $this->swaggerOpenApi->clean();
            $this->swaggerComponents->setSchemas([]);

            echo 'api docs server: file has been generated.' . PHP_EOL;
        }
    }

    protected function prepareHandler($handler): array
    {
        if (is_string($handler)) {
            if (str_contains($handler, '@')) {
                return explode('@', $handler);
            }
            return explode('::', $handler);
        }
        if (is_array($handler) && isset($handler[0], $handler[1])) {
            return $handler;
        }

        throw new RuntimeException('Handler not exist.');
    }
}
