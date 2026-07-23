<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class TodoList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $listName = '';   

    /** @var string[] */
    #[LiveProp(writable: true)]
    public array $todos = ['watering', 'mowing'];

    #[LiveProp(writable: true)]
    public string $newTodo = '';

    #[LiveAction]
    public function addTodo(): void
    {
        $newTodo = trim($this->newTodo);
        if ($newTodo === '') {
            return;
        }

        $this->todos[] = $newTodo;
        $this->newTodo = '';
    }
}