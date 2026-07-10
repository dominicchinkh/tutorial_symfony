<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class User
{
    public function __construct(

        #[Groups(['user:create', 'user:retrieve', 'user:update', 'user:delete'])]
        #[Assert\NotBlank]
        public string $firstName,

        #[Groups(['user:create', 'user:retrieve', 'user:update', 'user:delete'])]
        #[Assert\NotBlank]
        public string $lastName,

        #[Groups(['user:create', 'user:retrieve', 'user:update'])]
        #[Assert\GreaterThan(0)]
        public int $age,

        #[Groups(['user:create', 'user:retrieve', 'user:update'])]
        #[Assert\Choice(choices: ['admin', 'user', 'guest'])]
        public string $type = 'user',
    ) {
    }
}
