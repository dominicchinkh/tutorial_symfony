<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

// index  — key used by tagged locators and indexed iterators
// priority — higher numbers are injected first

#[AsTaggedItem(index: 'casual', priority: 20)]
class CasualGreetingHandler implements GreetingHandlerInterface
{
    public function greet(string $name): string
    {
        return 'Hey '.$name.'!';
    }
}
