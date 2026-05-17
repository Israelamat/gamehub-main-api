<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findPaginated(
        int $limit,
        int $offset
    ): array {
        return $this->findBy(
            [],
            ['id' => 'DESC'],
            $limit,
            $offset
        );
    }

    public function findFilteredGames(
        ?string $search,
        ?string $tag,
        ?float $maxPrice,
        ?string $sort,
        int $limit,
        int $offset
    ): array {
        $qb = $this->createQueryBuilder('g');

        if (!empty($search)) {
            $qb->andWhere(
                'g.title LIKE :search
             OR g.developer LIKE :search
             OR g.metadata LIKE :search'
            )
                ->setParameter('search', '%' . $search . '%');
        }

        if (!empty($tag) && $tag !== 'All') {
            $qb->andWhere('g.tags LIKE :tag')
                ->setParameter('tag', '%' . $tag . '%');
        }

        if (!empty($maxPrice)) {
            $qb->andWhere('g.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        switch ($sort) {

            case 'price_asc':
                $qb->orderBy('g.price', 'ASC');
                break;

            case 'price_desc':
                $qb->orderBy('g.price', 'DESC');
                break;

            case 'createdAt_desc':
                $qb->orderBy('g.createdAt', 'DESC');
                break;

            default:
                $qb->orderBy('g.id', 'DESC');
        }

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
