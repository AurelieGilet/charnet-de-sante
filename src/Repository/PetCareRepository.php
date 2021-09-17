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

    /**
     * @return PetCare[]
     */
    public function findCatFeedings($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.endDate IS NOT NULL and (petcare.foodType IS NOT NULL or petcare.foodBrand IS NOT NULL or petcare.foodQuantity IS NOT NULL)')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatCurrentFeedings($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.endDate IS NULL and (petcare.foodType IS NOT NULL or petcare.foodBrand IS NOT NULL or petcare.foodQuantity IS NOT NULL)')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatGroomings($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.grooming IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatClaws($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.claws IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatEyesEars($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.eyesEars IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatTeeth($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.teeth IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PetCare[]
     */
    public function findCatLitterbox($cat)
    {
        return $this->createQueryBuilder('petcare')
            ->andWhere('petcare.cat = :cat and petcare.litterbox IS NOT NULL')
            ->setParameter('cat', $cat)
            ->addOrderBy('petcare.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
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
