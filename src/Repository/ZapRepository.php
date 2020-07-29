<?php

namespace App\Repository;

use App\Entity\PublishableTrait;
use App\Entity\Zap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @param string $type
     * @return Zap|null
     * @throws NonUniqueResultException
     */
    public function getLatest(string $type = 'long'): ?Zap
    {
        return $this->createQueryBuilder('z')
            ->where('z.status = :status')
            ->setParameter('status', PublishableTrait::$STATUS_PUBLISHED)
            ->andWhere('z.type = :type')
            ->setParameter('type', $type)
            ->orderBy('z.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $type
     * @param int|null $zapIdFrom
     * @return array|null
     */
    public function getLatestByType(string $type = 'long', int $zapIdFrom = null): ?array
    {
        $qb = $this->createQueryBuilder('z')
            ->where('z.type = :type')
            ->setParameter('type', $type)
            ->orderBy('z.publishedAt', 'DESC')
            ->setMaxResults(1)
        ;

        if ($zapIdFrom) {
            $qb
                ->andWhere('z.id < :zapIdFrom')
                ->setParameter('zapIdFrom', $zapIdFrom)
            ;
        }

        return $qb
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * Zaps starting from a certain id and descending by publication date
     *
     * @param string $type
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    public function getDescending(string $type = 'long', int $limit = 3, int $offset = 0): ?array
    {
        return $this->createQueryBuilder('z')
            ->where('z.status = :status')
            ->setParameter('status', PublishableTrait::$STATUS_PUBLISHED)
            ->andWhere('z.type = :type')
            ->setParameter('type', $type)
            ->orderBy('z.publishedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
