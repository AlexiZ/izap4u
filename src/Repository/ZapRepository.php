<?php

namespace App\Repository;

use App\Entity\Zap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zap[]    findAll()
 * @method Zap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zap::class);
    }

    // /**
    //  * @return Zap[] Returns an array of Zap objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zap
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
