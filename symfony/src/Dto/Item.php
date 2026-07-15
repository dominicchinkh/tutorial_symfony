<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class Item
{
    public function __construct(
        #[Groups(['item:create', 'item:retrieve', 'item:update', 'item:delete'])]
        #[Assert\NotBlank]
        public string $name = '',

        #[Groups(['item:create', 'item:retrieve', 'item:update', 'item:delete'])]
        #[Assert\GreaterThan(0)]
        public int $price = 0,
    ) {
    }
}
