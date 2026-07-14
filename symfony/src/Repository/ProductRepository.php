<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private array $products = [];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);

        $this->products = [
            new Product('pencil', '12.50', 'A pencil'),
            new Product('paper', '0.50', 'A paper'),
        ];
    }

    /**
        * @return Product[] Returns an array of Product objects
        */
    public function findAll(): array
    {
        return $this->products;
    }

    /**
        * @return Product[] Returns an array of Product objects
        */
    public function search(string $query): array
    {
        return array_filter($this->products, function($product) use ($query) {
            return str_contains($product->getName(), $query);
        });
    }
}
