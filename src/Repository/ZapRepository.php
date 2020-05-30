<?php

namespace App\Repository;

use App\Entity\PublishableTrait;
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

    /**
     * Latest zap by publication date
     *
     * @return Zap|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLatest(): ?Zap
    {
        return $this->createQueryBuilder('z')
            ->where('z.status = :status')
            ->setParameter('status', PublishableTrait::$STATUS_PUBLISHED)
            ->orderBy('z.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
