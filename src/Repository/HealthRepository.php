<?php

namespace App\Repository;

use App\Entity\Health;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Health|null find($id, $lockMode = null, $lockVersion = null)
 * @method Health|null findOneBy(array $criteria, array $orderBy = null)
 * @method Health[]    findAll()
 * @method Health[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HealthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Health::class);
    }

    /**
     * @return Health[]
     */
    public function findCatVetVisits($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.vetVisitMotif IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatAllergies($cat, $currentDate)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.allergy IS NOT NULL and health.endDate IS NOT NULL and health.endDate < :currentDate')
            ->setParameters(array('cat' => $cat, 'currentDate' => $currentDate))
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatCurrentAllergies($cat, $currentDate)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.allergy IS NOT NULL and (health.endDate IS NULL or health.endDate > :currentDate)')
            ->setParameters(array('cat' => $cat, 'currentDate' => $currentDate))
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatDiseases($cat, $currentDate)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.disease IS NOT NULL and health.endDate IS NOT NULL and health.endDate < :currentDate')
            ->setParameters(array('cat' => $cat, 'currentDate' => $currentDate))
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatCurrentDiseases($cat, $currentDate)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.disease IS NOT NULL and (health.endDate IS NULL or health.endDate > :currentDate)')
            ->setParameters(array('cat' => $cat, 'currentDate' => $currentDate))
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatWounds($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.wound IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatSurgeries($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.surgery IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatAnalysis($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.analysis IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatDocuments($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.document IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('health.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Health[]
     */
    public function findCatFilenames($cat)
    {
        return $this->createQueryBuilder('health')
            ->andWhere('health.cat = :cat and health.document IS NOT NULL')
            ->setParameter('cat', $cat)
            ->select('health.document')
            ->getQuery()
            ->getResult()
        ;
    }
}
