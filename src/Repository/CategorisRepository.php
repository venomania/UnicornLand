<?php

namespace App\Repository;

use App\Entity\Categoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoris[]    findAll()
 * @method Categoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoris::class);
    }

    // /**
    //  * @return Categoris[] Returns an array of Categoris objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categoris
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
