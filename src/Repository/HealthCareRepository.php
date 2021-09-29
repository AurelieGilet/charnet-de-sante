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

    /**
     * @return HealthCare[]
     */
    public function findCatVaccines($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.vaccine IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatDewormer($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.dewormer IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatAntiparasite($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.parasite IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
