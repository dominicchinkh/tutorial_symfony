<?php

namespace App\Twig\Components\Button;

use App\Dto\Item;
use App\Dto\Notification;
use App\Entity\Product;
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

    public function getRandomNumber(): int
    {
        return rand(0, $this->max);
    }

    // If the Post object is persisted, its dehydrated to the entity's id and then 
    // hydrated back by querying the database. If the object is unpersisted, it's 
    // dehydrated to an empty array, then hydrated back by creating an empty object.

    // Allow setting writable to property names that should be writable. 

    #[LiveProp(writable: ['name', 'price', 'description'])]
    public ?Product $product = null;

    /* 
        // #[LiveProp(writable: ['allow_markdown'])]
        // public array $options = ['allow_markdown' => true, 'allow_html' => false];
    */

    // Arrays of Doctrine entities and other "simple" values like DateTime are also 
    // supported, as long as the LiveProp has proper PHPDoc that LiveComponents can 
    // read.

    /** @var Product[] */
    #[LiveProp]
    public ?array $products = [];

    // Checkbox/select
    #[LiveProp]
    public array $todoItems = ['Train tiger', 'Feed tiger', 'Pet tiger'];

    /** @var string[] */
    #[LiveProp(writable: true)]
    public array $checkedTodoItems = [];

    #[LiveProp(writable: true)]
    public ?string $selectedTodoItem = null;

    // Datetime format
    #[LiveProp(writable: true, format: 'Y-m-d')]
    public ?\DateTimeInterface $publishOn = null;

    /*
        If you want the user to be able to change the Post and certain properties, use 
        the special LiveProp::IDENTITY constant:
        
        #[LiveProp(writable: [LiveProp::IDENTITY, 'name', 'price'])]
    */
    
    // DTO
    #[LiveProp(writable: ['name', 'price'])]
    public ?Item $item1 = null;

    /**
     * @var Item[]
     */
    #[LiveProp(writable: true)]
    public array $items;

    // hydrateWith/dehydrateWith
    #[LiveProp(
        writable: ['name', 'price'], 
        hydrateWith: 'hydrateItem', 
        dehydrateWith: 'dehydrateItem')
    ]
    public ?Item $item2 = null;

    public function dehydrateItem(Item $item)
    {
        return [
            'name'  => $item->name,
            'price' => $item->price
        ];
    }

    public function hydrateItem($data): Item
    {
        return new Item($data['name'], $data['price']);
    }

    // Test hydration extension: symfony/src/Extension/NotificationHydration.php
    #[LiveProp(writable: ['message', 'type'])]
    public Notification $notification;
}