<?php

namespace App\Repository;

use App\Entity\Blague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blague>
 */
class BlagueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blague::class);
    }

    public function findRandomBlague()
    {
        // total of blagues
        $count = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($count == 0) {
            return null;
        }

        // random offset
        $randomOffset = rand(0, $count - 1);

        // return random blague 
        return $this->createQueryBuilder('b')
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
