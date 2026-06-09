<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Constant\AppConstant;
use App\Entity\PullRequest;
use App\Entity\TableProject;
use App\Enum\PullRequestState;
use App\Enum\TableProjectState;

// https://symfony.com/doc/current/workflow/workflow-and-state-machine.html

return App::config([
    'framework' => [
        'workflows' => [
            'pull_request' => [
                'type' => 'state_machine',

                // Setting the audit_trail.enabled option to true makes the application generate 
                // detailed log messages for the workflow activity.
                'audit_trail' => [
                    'enabled' => true,
                ],

                // A single state marking store uses a string to store the data. A multiple state 
                // marking store uses an array to store the data. If no state marking store is defined 
                // you have to return null in both cases.

                // For example:
                //   App\Entity\BlogPost::getState(): ?array
                //   App\Entity\BlogPost::getState(): ?string

                'marking_store' => [
                    'type' => 'method',
                    'property' => 'state',
                ],

                // The "supports" option is useful only if you are using Twig functions ('workflow_*')
                'supports' => [PullRequest::class],

                'initial_marking' => PullRequestState::Start,
                'places' => [
                    PullRequestState::Start,
                    PullRequestState::Coding,
                    PullRequestState::Test,
                    PullRequestState::Review,
                    PullRequestState::Merged,
                    PullRequestState::Closed,
                ],

                // with constants:
                // 'places' => 'App\Constant\AppConstant::PULL_REQUEST_STATE_*',

                // with enums:
                // 'places' => PullRequestState::cases(),

                'transitions' => [
                    'submit' => [
                        'from' => PullRequestState::Start,
                        'to'   => PullRequestState::Test,
                    ],
                    'update' => [
                        'from' => [
                            PullRequestState::Coding, 
                            PullRequestState::Test, 
                            PullRequestState::Review
                        ],
                        'to' => PullRequestState::Test,
                    ],
                    'wait_for_review' => [
                        'from' => PullRequestState::Test,
                        'to'   => PullRequestState::Review,
                    ],
                    'request_change' => [
                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Coding,
                    ],
                    'accept' => [
                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Merged,
                    ],
                    'reject' => [
                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Closed,
                    ],
                    'reopen' => [
                        'from' => PullRequestState::Closed,
                        'to' => PullRequestState::Review,
                    ],
                ],
            ],
            'make_table' => [
                'type' => 'workflow',
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'marking',
                ],
                'supports' => [TableProject::class],
                'initial_marking' => TableProjectState::Init,
                'places' => [
                    TableProjectState::Init,
                    TableProjectState::PrepareLeg,
                    TableProjectState::PrepareTop,
                    TableProjectState::StopwatchRunning,
                    TableProjectState::LegCreated,
                    TableProjectState::TopCreated,
                    TableProjectState::Finished,
                ],
                'transitions' => [
                    'start' => [
                        'from' => TableProjectState::Init,
                        'to' => [
                            ['place' => TableProjectState::PrepareLeg, 'weight' => 4],
                            ['place' => TableProjectState::PrepareTop, 'weight' => 1],
                            ['place' => TableProjectState::StopwatchRunning, 'weight' => 1],
                        ],
                    ],
                    'build_leg' => [
                        'from' => TableProjectState::PrepareLeg,
                        'to' => TableProjectState::LegCreated,
                    ],
                    'build_top' => [
                        'from' => TableProjectState::PrepareTop,
                        'to' => TableProjectState::TopCreated,
                    ],
                    'join' => [
                        'from' => [
                            ['place' => TableProjectState::LegCreated, 'weight' => 4],
                            ['place' => TableProjectState::TopCreated, 'weight' => 1],
                            ['place' => TableProjectState::StopwatchRunning, 'weight' => 1],
                        ],
                        'to' => TableProjectState::Finished,
                    ]
                ]
            ]
        ]
    ]
]);
