<?php

namespace App\Repository;

use App\Entity\HealthCare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HealthCare|null find($id, $lockMode = null, $lockVersion = null)
 * @method HealthCare|null findOneBy(array $criteria, array $orderBy = null)
 * @method HealthCare[]    findAll()
 * @method HealthCare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HealthCareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HealthCare::class);
    }

    // /**
    //  * @return HealthCare[] Returns an array of HealthCare objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HealthCare
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
