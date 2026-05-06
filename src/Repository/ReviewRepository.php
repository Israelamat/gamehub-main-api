<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findByGameAppId(int $appId): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.game', 'g')
            ->where('g.appId = :appId')
            ->setParameter('appId', $appId)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
