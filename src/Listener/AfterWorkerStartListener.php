<?php

declare(strict_types=1);

namespace Baoziyoo\Hyperf\ApiDocs\Listener;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\AfterWorkerStart;
use Hyperf\Server\Event\MainCoroutineServerStart;

class AfterWorkerStartListener implements ListenerInterface
{
    public function __construct(private readonly StdoutLoggerInterface $logger)
    {
    }

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            AfterWorkerStart::class,
            MainCoroutineServerStart::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event): void
    {
        /** @var AfterWorkerStart|MainCoroutineServerStart $event */
        $isCoroutineServer = $event instanceof MainCoroutineServerStart;
        if ($isCoroutineServer || $event->workerId === 0) {
            BootAppRouteListener::$massage && $this->logger->info(BootAppRouteListener::$massage);
        }
    }
}
