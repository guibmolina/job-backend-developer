<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Delete;

use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Repositories\ProductRepository;

class Delete
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): void
    {
        $product = $this->repository->findProductById($DTO->id);

        if (!$product) {
            throw new NotFoundProductException("Not found product $DTO->id");
        }

        $this->repository->delete($product);
    }
}
