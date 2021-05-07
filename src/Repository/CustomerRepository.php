<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findCustomerToAReseller(int $value)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id,c.name,c.lastName,c.createdAt')
            ->andWhere('c.resellers = :value')
            ->setParameter('value', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
