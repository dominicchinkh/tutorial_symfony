<?php

namespace App\Service\Tag;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

class GreetingHandlerCollection
{
    /**
     * @param iterable<GreetingHandlerInterface>        $handlers
     * @param iterable<string, GreetingHandlerInterface> $indexedHandlers
     * @param ServiceLocator<GreetingHandlerInterface>   $locator
     */
    public function __construct(
        
        // Every app.greeting_handler service, highest priority first.
        // DebugGreetingHandler is omitted here via exclude.
        #[AutowireIterator('app.greeting_handler', exclude: [DebugGreetingHandler::class])]
        private iterable $handlers,

        // Same collection, keyed by the AsTaggedItem index.
        #[AutowireIterator('app.greeting_handler', indexAttribute: 'key', exclude: [DebugGreetingHandler::class])]
        private iterable $indexedHandlers,

        // Fetch one handler by its AsTaggedItem index. Includes the debug handler.
        #[AutowireLocator('app.greeting_handler')]
        private ServiceLocator $locator,
    ) {
    }

    /**
     * @return list<string>
     */
    public function greetAll(string $name): array
    {
        return array_map(
            static fn (GreetingHandlerInterface $handler): string => $handler->greet($name),
            iterator_to_array($this->handlers),
        );
    }

    /**
     * @return array<string, string>
     */
    public function greetByIndex(string $name): array
    {
        return array_map(
            static fn (GreetingHandlerInterface $handler): string => $handler->greet($name),
            iterator_to_array($this->indexedHandlers),
        );
    }

    public function greetWith(string $index, string $name): ?string
    {
        if (!$this->locator->has($index)) {
            return null;
        }

        $handler = $this->locator->get($index);
        if (!$handler instanceof GreetingHandlerInterface) {
            throw new \LogicException(sprintf('Service "%s" must implement %s.', $index, GreetingHandlerInterface::class));
        }

        return $handler->greet($name);
    }

    /**
     * @return list<string>
     */
    public function getLocatorIndexes(): array
    {
        return array_keys($this->locator->getProvidedServices());
    }
}
