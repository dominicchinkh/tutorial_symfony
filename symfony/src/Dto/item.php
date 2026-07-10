<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class ItemDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Groups(['user:create', 'admin:create'])]
        public string $name,

        #[Assert\GreaterThan(0)]
        #[Groups(['admin:create'])]
        public int $price
    ) {
    }
}
