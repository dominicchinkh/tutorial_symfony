<?php

namespace App\Twig\Components\Button;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class EventEmitter
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveAction]
    public function saveProduct()
    {
        $price = $this->getRandomNumber();

        $this->emit('productAdded', [
            // You can also pass extra (scalar) data to the listeners
            'price' => $price,
        ]);

        // Dispatch a JavaScript event
        $this->dispatchBrowserEvent('product:created', [
            'price' => $price,
        ]);
    }

    #[LiveProp]
    public int $productPrice = 0;
    
    #[LiveListener('productAdded')]
    public function incrementProductPrice(#[LiveArg] int $price)
    {
        $this->productPrice += $price;
    }

    public function getRandomNumber(): int
    {
        return rand(0, 100);
    }
}
