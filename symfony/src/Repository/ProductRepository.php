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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
        * @return Product[] Returns an array of Product objects
        */
    public function findByKeyword(): array
    {
        return [
            new Product(1, 'pencil', '12.50', 'A pencil'),
            new Product(2, 'paper', '0.50', 'A paper')
        ];
    }

}
