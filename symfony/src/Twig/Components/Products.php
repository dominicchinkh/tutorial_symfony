<?php

namespace App\Twig\Components;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Products
{
    use DefaultActionTrait;

    // Because components are services, normal dependency injection can be used. However, 
    // each component service is registered with shared: false. That means that you can 
    // safely render the same component multiple times with different data because each 
    // component will be an independent instance.
    
    // As a general rule: use readonly for your services, but not for your component class 
    // or public properties that receive props.
    
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        // an example method that returns an array of Products
        return $this->productRepository->findAll();
    }
}
