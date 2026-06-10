<?php

namespace App\EventSubscriber;

use App\Entity\PullRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class WorkflowGuardSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function guardSubmit(GuardEvent $event): void
    {
        /** @var PullRequest $pullRequest */
        $pullRequest = $event->getSubject();
        $title = $pullRequest->getTitle();

        if (empty($title)) {
            $event->setBlocked(true, 'This pull request cannot be marked as tested because it has no title.');

            $this->logger->alert(sprintf(
                'Guard Subscriber: Pull request (id: "%s") performed transition "%s" from "%s" to "%s". %s Blocking the transition.',
                $event->getSubject()->getId(),
                $event->getTransition()->getName(),
                implode(', ', array_keys($event->getMarking()->getPlaces())),
                implode(', ', $event->getTransition()?->getTos() ?? []),
                $event->getMetadata('no_title_message', $event->getTransition()),
            ));
        }
    }

    public static function getSubscribedEvents(): array
    {
        // https://symfony.com/doc/current/workflow.html#using-events
        return [
            'workflow.pull_request.guard.submit' => ['guardSubmit'],
        ];
    }
}
