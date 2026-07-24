<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class TodoFooter
{
    use DefaultActionTrait;

    // With `updateFromParent`, when the parent component re-renders, if the value of the count prop changes, the child 
    // will make a second Ajax request to re-render itself

    #[LiveProp(updateFromParent: true)]
    public int $count = 0;

    // Note: actions in a child do not affect the parent
}