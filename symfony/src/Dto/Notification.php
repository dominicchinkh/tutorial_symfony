<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Notification
{
    public function __construct(

        #[Assert\NotBlank]
        public string $message = '',

        #[Assert\NotBlank]
        public string $type = ''
    ) {
    }

    public function toJson()
    {
        return json_encode([
            'message' => $this->message,
            'type'    => $this->type
        ]);
    }
}
