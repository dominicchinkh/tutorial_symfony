<?php

namespace App\Resolver;

use App\Dto\ItemDto;
use Psr\Log\LoggerInterface;

class ItemDtoResolver
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function resolve(ItemDto $itemDto): void
    {
        $this->logger->info('ItemDto resolved', ['itemDto' => $itemDto]);
    }
}
