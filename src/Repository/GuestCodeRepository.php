<?php

namespace App\Repository;

use App\Entity\GuestCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuestCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuestCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuestCode[]    findAll()
 * @method GuestCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuestCode::class);
    }

    // /**
    //  * @return GuestCode[] Returns an array of GuestCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GuestCode
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
