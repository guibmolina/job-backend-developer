<?php

declare(strict_types=1);

namespace Domain\Product\List;

use ArrayIterator;
use Domain\Product\Entities\ProductEntity;
use IteratorAggregate;
use Traversable;

/** @implements IteratorAggregate<Setting> */
class ProductList implements IteratorAggregate
{
    /** @var array<ProductEntity> */
    private array $products;

    public function __construct()
    {
        $this->products = [];
    }

    public function add(ProductEntity $product): void
    {
        $this->products[] = $product;
    }

    /** @return array<ProductEntity> */
    public function products(): array
    {
        return $this->products;
    }

    /** @return Traversable<ProductEntity> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->products);
    }
}
