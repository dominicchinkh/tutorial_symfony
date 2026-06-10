<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\LeaveEvent;

class WorkflowLoggerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function onLeave(Event $event): void
    {
        $this->logger->alert(sprintf(
            'Blog post (id: "%s") performed transition "%s" from "%s" to "%s"',
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()?->getTos() ?? [])
        ));
    }

    public static function getSubscribedEvents(): array
    {
        // https://symfony.com/doc/current/workflow.html#using-events
        return [
            // LeaveEvent::getName('pull_request') => 'onLeave',

            // if you prefer, you can write the event name manually like this:
            // 'workflow.pull_request.leave' => 'onLeave',
        ];
    }
}