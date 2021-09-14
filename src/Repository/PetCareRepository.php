<?php

namespace App\Repository;

use App\Entity\PetCare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PetCare|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetCare|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetCare[]    findAll()
 * @method PetCare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetCareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetCare::class);
    }

    // /**
    //  * @return PetCare[] Returns an array of PetCare objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PetCare
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
