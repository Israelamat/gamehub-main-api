<?php

namespace App\Repository;

use App\Entity\CommunityGame;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<CommunityGame>
 */
class CommunityGameRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunityGame::class);
    }
}
