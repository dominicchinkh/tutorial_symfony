<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'formal', priority: 10)]
class FormalGreetingHandler implements GreetingHandlerInterface
{
    public function greet(string $name): string
    {
        return 'Good day, '.$name.'.';
    }
}
