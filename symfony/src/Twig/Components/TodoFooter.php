<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class TodoFooter
{
    use DefaultActionTrait;

    // Re-renders when the parent passes a new count (e.g. after adding a todo).
    #[LiveProp(updateFromParent: true)]
    public int $count = 0;

    // Writable in the child; synced to the parent's listName via dataModel.
    #[LiveProp(writable: true)]
    public string $listName = '';

    // Writable prop that survives count-driven child re-renders (not updateFromParent).
    #[LiveProp(writable: true)]
    public bool $isVisible = true;

    #[LiveAction]
    public function toggleVisibility(): void
    {
        $this->isVisible = !$this->isVisible;
    }
}
