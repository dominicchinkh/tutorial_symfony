<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class UserDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstname,

        #[Assert\NotBlank]
        public string $lastname,

        #[Assert\GreaterThan(0)]
        public int $age,

        #[Assert\Choice(choices: ['admin', 'user', 'guest'])]
        public string $type
    ) {
    }
}
