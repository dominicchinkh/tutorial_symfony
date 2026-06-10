<?php

namespace App\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\Attribute\AsTransitionListener;
use Symfony\Component\Workflow\Event\TransitionEvent;

class PullRequestWorkflowListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[AsTransitionListener('pull_request', 'submit')]
    public function onPullRequestTransition(TransitionEvent $event): void
    {
        $this->logger->alert(sprintf(
            'Listener: Pull request (id: "%s") performed transition "%s" from "%s" to "%s"',
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()?->getTos() ?? [])
        ));
    }
}