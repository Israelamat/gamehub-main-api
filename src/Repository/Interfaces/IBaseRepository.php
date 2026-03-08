<?php

namespace App\Repository\Interfaces;

/**
 * @template T
 */
interface IBaseRepository
{
    /** @param T $entity */
    public function create(object $entity): object;

    /** @return T|null */
    public function findById(int $id): ?object;

    /** @return T[] */
    public function findAllSafe(int $limit = 20): array;

    /** @return T[] */
    public function findByCriteria(...$criteria): array;

    /** @param T $entity */
    public function update(object $entity, array $data): object;

    /** @param T $entity */
    public function delete(object $entity): void;
}
