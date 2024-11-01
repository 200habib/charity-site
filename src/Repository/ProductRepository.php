<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Product::class);
        $this->security = $security;
    }

    public function findByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(string $sortPrice, bool $showSellerProducts): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->orderBy('p.price', $sortPrice === 'asc' ? 'ASC' : 'DESC');

        if ($showSellerProducts) {
            $user = $this->security->getUser();

            if ($user) {
                $qb
                    ->leftJoin('p.seller', 'seller')
                    ->andWhere('seller.id = :currentUserId')
                    ->setParameter('currentUserId', $user->getId());
            }
        }

        return $qb->getQuery()->getResult();
    }

}
