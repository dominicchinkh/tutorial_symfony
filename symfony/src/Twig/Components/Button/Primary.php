<?php

namespace App\Twig\Components\Button;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Primary
{
    use DefaultActionTrait;
}
