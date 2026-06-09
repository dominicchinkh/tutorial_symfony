<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

// https://symfony.com/doc/current/workflow/workflow-and-state-machine.html


return App::config([
    'framework' => [
        'workflows' => [
            'pull_request' => [
                'type' => 'state_machine',
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'currentPlace',
                ],
                // The "supports" option is useful only if you are using Twig functions ('workflow_*')
                'supports' => ['App\Entity\PullRequest'],
                'initial_marking' => 'start',
                'places' => [
                    'start',
                    'coding',
                    'test',
                    'review',
                    'merged',
                    'closed',
                ],
                'transitions' => [
                    'submit' => [
                        'from' => 'start',
                        'to' => 'test',
                    ],
                    'update' => [
                        'from' => ['coding', 'test', 'review'],
                        'to' => 'test',
                    ],
                    'wait_for_review' => [
                        'from' => 'test',
                        'to' => 'review',
                    ],
                    'request_change' => [
                        'from' => 'review',
                        'to' => 'coding',
                    ],
                    'accept' => [
                        'from' => 'review',
                        'to' => 'merged',
                    ],
                    'reject' => [
                        'from' => 'review',
                        'to' => 'closed',
                    ],
                    'reopen' => [
                        'from' => 'closed',
                        'to' => 'review',
                    ],
                ],
            ],
        ],
    ],
]);
