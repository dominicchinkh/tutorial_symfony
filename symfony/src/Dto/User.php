<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class User
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\NotBlank]
        public string $lastName,

        #[Assert\GreaterThan(0)]
        public int $age,

        #[Assert\Choice(choices: ['admin', 'user', 'guest'])]
        public string $type = 'user',
    ) {
    }
}
