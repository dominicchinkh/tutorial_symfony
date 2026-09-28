<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

// Tagged like the others, then left out of the iterators in GreetingHandlerCollection.
// It stays available through the service locator.

#[AsTaggedItem(index: 'debug', priority: 100)]
class DebugGreetingHandler implements GreetingHandlerInterface
{
    public function greet(string $name): string
    {
        return '[debug] '.$name;
    }
}
