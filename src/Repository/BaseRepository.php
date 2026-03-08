<?php

namespace App\Repository;

use App\Repository\Interfaces\IBaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 * @implements IBaseRepository<T>
 */
abstract class BaseRepository extends ServiceEntityRepository implements IBaseRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function create(object $entity): object
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function findById(int $id): ?object
    {
        return parent::find($id);
    }

    public function findAllSafe(int $limit = 20): array
    {
        $totalRows = $this->count([]);
        $maxOffset = max(0, $totalRows - $limit);
        $randomOffset = rand(0, $maxOffset);

        return $this->findBy(
            [],
            ['id' => 'ASC'],
            $limit,
            $randomOffset
        );
    }

    /**
     * Generic criteria search for any entity.
     * It handles arrays, objects, or named arguments.
     */
    public function findByCriteria(...$criteria): array
    {
        try {
            // Normalize criteria to array for know if it is an array or an object
            if (count($criteria) === 1 && (is_array(reset($criteria)) || is_object(reset($criteria)))) {
                $criteria = (array) reset($criteria);
            }

            if (empty($criteria)) {
                return $this->findAllSafe();
            }
            $filters = array_filter($criteria, fn($value) => !is_null($value));

            return $this->findBy($filters, ['id' => 'DESC'], 20);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function update(object $entity, array $data): object
    {
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function delete(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
