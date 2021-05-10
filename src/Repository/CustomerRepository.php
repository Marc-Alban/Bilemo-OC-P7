<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
// use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
// use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator as ApiPlatformPaginator;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{

    // const ITEMS_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }


    //Sans pagination 
    public function findByReseller(int $value)
    {
         return $this->createQueryBuilder('c')
            ->select('c.id,c.name,c.lastName,c.createdAt')
            ->andWhere('c.resellers = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
    }

    //Avec pagination mais erreur ... 
    // public function findByReseller(int $value, int $page = 1)
    // {

    //     $firstResult = ($page -1) * self::ITEMS_PER_PAGE;

    //      $queryBuilder = $this->createQueryBuilder('c');
    //      $queryBuilder->select('c.id,c.name,c.lastName,c.createdAt')
    //         ->andWhere('c.resellers = :value')
    //         ->setParameter('value', $value)
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();

    //     $query = $queryBuilder->getQuery()
    //         ->setFirstResult($firstResult)
    //         ->setMaxResults(self::ITEMS_PER_PAGE);

    //     $doctrinePaginator = new DoctrinePaginator($query);
    //     $paginator = new ApiPlatformPaginator($doctrinePaginator);

    //     return $paginator;     

    // }

}
