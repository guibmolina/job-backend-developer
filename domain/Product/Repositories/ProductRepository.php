<?php

declare(strict_types=1);

namespace Domain\Product\Repositories;

use Domain\Product\Entities\ProductEntity;

interface ProductRepository extends BaseRepository
{
    public function create(ProductEntity $entity): ProductEntity;

    public function findProductByName(string $name): ?ProductEntity;

    public function delete(ProductEntity $entity): void;

    public function update(int $productId, ProductEntity $entity): ProductEntity;
}
