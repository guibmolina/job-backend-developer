<?php

declare(strict_types=1);

namespace Domain\Product\Repositories;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\List\ProductList;

interface BaseRepository
{
    public function findProductById(int $id): ?ProductEntity;

    public function findProductsBy(array $searchFilters, ?bool $withImage): ProductList;
}
