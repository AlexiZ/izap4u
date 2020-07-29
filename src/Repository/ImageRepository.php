<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\PublishableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Latest image(s) by publication date
     *
     * @param int $number
     * @return Image|null
     */
    public function getLatest(int $number = 1)
    {
        return $this->createQueryBuilder('i')
            ->where('i.status = :status')
            ->setParameter('status', PublishableTrait::$STATUS_PUBLISHED)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int|null $imageIdFrom
     * @return array|null
     */
    public function getLatestFromId(int $imageIdFrom = null): ?array
    {
        return $this->createQueryBuilder('i')
            ->where('i.id < :imageIdFrom')
            ->setParameter('imageIdFrom', $imageIdFrom)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * Images starting from a certain id and descending by publication date
     *
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    public function getDescending(int $limit = 3, int $offset = 0): ?array
    {
        return $this->createQueryBuilder('i')
            ->where('i.status = :status')
            ->setParameter('status', PublishableTrait::$STATUS_PUBLISHED)
            ->orderBy('i.publishedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
