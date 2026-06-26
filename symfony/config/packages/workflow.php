<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Constant\AppConstant;
use App\Entity\PullRequest;
use App\Entity\TableProject;
use App\Enum\PullRequestState;
use App\Enum\TableProjectState;
use App\Validator\Workflow\PullRequestValidator;

// https://symfony.com/doc/current/workflow/workflow-and-state-machine.html

return App::config([
    'framework' => [
        'workflows' => [
            'pull_request' => [

                'definition_validators' => [
                    PullRequestValidator::class,
                ],

                // You can store arbitrary metadata in workflows, their places, and their transitions 
                // using the metadata option.
                'metadata' => [
                    'title' => 'Pull Request Workflow',
                ],

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
                //   App\Entity\PullRequest::getState(): ?array
                //   App\Entity\PullRequest::getState(): ?string

                'marking_store' => [
                    'type' => 'method',
                    'property' => 'state',
                ],

                // The "supports" option tells the framework which PHP classes or entities this specific 
                // workflow can be applied to.

                // Without the supports option, Twig doesn't know which workflow definition to look up when 
                // you hand it an entity, e.g. {{ workflow_can('submit', pullRequest) }}.
                
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
                        'metadata' => [

                            // This message is passed to the guard event and can be used in the event subscriber to 
                            // provide more context about why the transition is blocked.
                            'no_title_message' => 'You cannot move a pull request without a title to the "Test" state.',
                        ],
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

                        # The transition is allowed only if the current user has the ROLE_REVIEWER role.
                        'guard' => "is_granted('ROLE_REVIEWER')",

                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Coding,
                    ],
                    'accept' => [

                        # or "is_remember_me", "is_fully_authenticated", "is_granted", "is_valid"
                        # 'guard' => "is_authenticated",

                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Merged,
                    ],
                    'reject' => [

                        # or any valid expression language with "subject" referring to the supported object
                        'guard' => "subject.isRejectable()",

                        'from' => PullRequestState::Review,
                        'to' => PullRequestState::Closed,
                    ],
                    'reopen' => [
                        'from' => PullRequestState::Closed,
                        'to' => PullRequestState::Review,
                    ],
                ],

                # You can pass one or more event names
                'events_to_dispatch' => ['workflow.leave', 'workflow.completed'],

                # Pass an empty array to not dispatch any event
                // 'events_to_dispatch' => []
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
