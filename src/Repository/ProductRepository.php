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

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }


    public function findByFilters(string $sortPrice, bool $showSellerProducts)
    {
        $qb = $this->createQueryBuilder('p');


        if ($sortPrice === 'asc') {
            $qb->orderBy('p.price', 'ASC');
        } else {
            $qb->orderBy('p.price', 'DESC');
        }

        if ($showSellerProducts) {
            $qb
                ->leftJoin('p.seller', 'seller')
                ->andWhere('seller.id = :currentUserId')
                ->setParameter('currentUserId', $this->security->getUser()->getId());
        }

        return $qb->getQuery()->getResult();
    }
}
