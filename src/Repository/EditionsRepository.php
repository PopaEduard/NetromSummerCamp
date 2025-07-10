<?php

namespace App\Repository;

use App\Entity\Editions;
use App\Entity\Festival;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Editions>
 */
class EditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Editions::class);
    }

    public function findLastEdition()
    {
        $qb = $this->createQueryBuilder('e');

        $sub = $this->createQueryBuilder('e2')
            ->select('MAX(e2.end_date)')
            ->where('e2.festival_id = e.festival_id')
            ->getDQL();

        $qb->where("e.end_date = ($sub)");

        return $qb->getQuery()->getResult();
    }

    public function findOrderedByFestival($festivalId)
    {
        return $this->createQueryBuilder('e')
            ->where('e.festival_id = :festivalId')
            ->setParameter('festivalId', $festivalId)
            ->orderBy('e.end_date', 'DESC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Editions[] Returns an array of Editions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Editions
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
