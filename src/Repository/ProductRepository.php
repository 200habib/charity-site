<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Pagination\PaginationInterface;
/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security, public PaginatorInterface $paginator)
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

    public function findByFilters(array $filters, array $sort = [])
    {
        $queryBuilder = $this->createQueryBuilder('p');
    
        // Applica i filtri
        foreach ($filters as $field => $value) {
            $queryBuilder->andWhere("p.$field = :$field")
                         ->setParameter($field, $value);
        }
    
        // Applica l'ordinamento
        foreach ($sort as $field => $order) {
            $queryBuilder->addOrderBy("p.$field", $order);
        }
    
        return $queryBuilder->getQuery()->getResult();
    }
    


    public function Paginate(int $page = 1, int $limit = 1) : PaginationInterface {
        return $this->paginator->paginate(
            $this->createQueryBuilder('l'),
            $page,
            $limit
        );
    }
    
    

}
