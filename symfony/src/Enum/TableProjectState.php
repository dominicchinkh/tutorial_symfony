<?php

namespace App\Enum;

enum TableProjectState: string
{
    case Init = 'init';
    case PrepareLeg = 'prepare_leg';
    case PrepareTop = 'prepare_top';
    case StopwatchRunning = 'stopwatch_running';
    case LegCreated = 'leg_created';
    case TopCreated = 'top_created';
    case Finished = 'finished';
}
