<?php

namespace App\Twig\Components\Button;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class RandomNumber
{
    use DefaultActionTrait;

    // A property with the LiveProp attribute becomes a "stateful" property for
    // this component. In other words, each time when the component re-renders, 
    // it will remember the original values for the `$max`

    // By default, a LiveProp is "read only". For security purposes, a user cannot 
    // change the value of a LiveProp and re-render the component unless you allow 
    // it with the `writable=true` option:

    #[LiveProp(writable: true)]
    public int $max = 1000;

    // If the Post object is persisted, its dehydrated to the entity's id and then 
    // hydrated back by querying the database. If the object is unpersisted, it's 
    // dehydrated to an empty array, then hydrated back by creating an empty object.

    #[LiveProp]
    public Product $product;

    // Arrays of Doctrine entities and other "simple" values like DateTime are also 
    // supported, as long as the LiveProp has proper PHPDoc that LiveComponents can 
    // read.

    /** @var Product[] */
    public $products = [];

    public function getRandomNumber(): int
    {
        return rand(0, $this->max);
    }
}