<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'shout', priority: 0)]
class ShoutGreetingHandler implements GreetingHandlerInterface
{
    public function greet(string $name): string
    {
        return 'HELLO '.mb_strtoupper($name).'!';
    }
}
