<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param int|null $zapIdFrom
     * @return array|null
     */
    public function getLatestById(int $zapIdFrom = null): ?array
    {
        return $this->createQueryBuilder('z')
            ->where('z.id < :zapIdFrom')
            ->setParameter('zapIdFrom', $zapIdFrom)
            ->orderBy('z.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
