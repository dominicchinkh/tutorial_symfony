<?php

namespace App\Enum;

enum PullRequestState: string
{
    case Start = 'start';
    case Coding = 'coding';
    case Test = 'test';
    case Review = 'review';
    case Merged = 'merged';
    case Closed = 'closed';
}
