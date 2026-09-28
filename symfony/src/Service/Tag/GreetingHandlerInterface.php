<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

// Every class that implements this interface is tagged app.greeting_handler
// because services.yaml enables autoconfigure.
//
// Inspect the tagged services with:
//   php bin/console debug:container --tag=app.greeting_handler

#[AutoconfigureTag('app.greeting_handler')]
interface GreetingHandlerInterface
{
    public function greet(string $name): string;
}
