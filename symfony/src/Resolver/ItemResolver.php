<?php

namespace App\Resolver;

use App\Dto\Item;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ItemResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // 1. (Optional but recommended) Ensure we are mapping to the correct class
        if ($argument->getType() !== Item::class) {
            return [];
        }

        // 2. Extract the JSON payload
        try {
            // toArray() safely decodes the JSON and throws an exception if it's invalid
            $payload = $request->toArray();

        } catch (\Exception $e) {
            throw new BadRequestHttpException('Invalid JSON payload.');
        }

        // 3. Validate that the required fields actually exist in the payload
        if (!isset($payload['name']) || !isset($payload['price'])) {
            throw new BadRequestHttpException('Missing required fields: name and price.');
        }

        // 4. Instantiate your DTO with the extracted data
        $item = new Item(
            (string) $payload['name'], 
            (int) $payload['price']
        );

        // 5. Log the action as you originally intended!
        $this->logger->info('Item resolved from request payload', [
            'item_name' => $item->name, 
            'item_price' => $item->price
        ]);

        // 6. Yield the final object to Symfony
        yield $item;
    }
}