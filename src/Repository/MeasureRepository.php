<?php

namespace App\Repository;

use App\Entity\Measure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Measure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measure[]    findAll()
 * @method Measure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measure::class);
    }

    /**
     * @return Measure[]
     */
    public function findCatWeights($cat)
    {
        return $this->createQueryBuilder('measure')
            ->andWhere('measure.cat = :cat and measure.weight IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('measure.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Measure[]
     */
    public function findCatTemperatures($cat)
    {
        return $this->createQueryBuilder('measure')
            ->andWhere('measure.cat = :cat and measure.temperature IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('measure.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Measure[]
     */
    public function findCatHeat($cat)
    {
        return $this->createQueryBuilder('measure')
            ->andWhere('measure.cat = :cat and measure.isInHeat = TRUE and measure.heatEndDate IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('measure.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Measure[]
     */
    public function findCatCurrentHeat($cat)
    {
        return $this->createQueryBuilder('measure')
            ->andWhere('measure.cat = :cat and measure.isInHeat = TRUE and measure.heatEndDate IS NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('measure.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
