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
            ->andWhere("healthcare.cat = :cat and healthcare.vaccine != ' '")
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatDewormers($cat)
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
    public function findCatAntiparasites($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.parasite IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatTreatments($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.treatment IS NOT NULL and healthcare.endDate IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatCurrentTreatments($cat, $currentDate)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.treatment IS NOT NULL and (healthcare.endDate IS NULL or healthcare.endDate > :currentDate)')
            ->setParameters(array('cat' => $cat, 'currentDate' => $currentDate))
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HealthCare[]
     */
    public function findCatDescaling($cat)
    {
        return $this->createQueryBuilder('healthcare')
            ->andWhere('healthcare.cat = :cat and healthcare.descaling IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('healthcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
