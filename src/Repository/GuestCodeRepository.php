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
}
