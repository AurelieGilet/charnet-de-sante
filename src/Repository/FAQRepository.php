<?php

namespace App\Repository;

use App\Entity\FAQ;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FAQ|null find($id, $lockMode = null, $lockVersion = null)
 * @method FAQ|null findOneBy(array $criteria, array $orderBy = null)
 * @method FAQ[]    findAll()
 * @method FAQ[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FAQRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FAQ::class);
    }

    /**
     * @return FAQ[]
     */
    public function findFAQBySearch($criteria)
    {
        $query = $this
                ->createQueryBuilder('faq');

        if (!empty($criteria)) {
            for ($i = 0; $i < count($criteria); $i++) {
                $query = $query
                    ->orWhere('faq.question LIKE :word'.$i)
                    ->setParameter('word'.$i , "%" . $criteria[$i] . "%");
            }
        }

        $query = $query->orderBy('faq.id', 'DESC');

        $query = $query->getQuery();

        return $query->getResult();
    }
}
