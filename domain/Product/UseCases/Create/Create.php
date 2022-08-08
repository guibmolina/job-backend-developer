<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Create;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\ProductAlreadyExistException;
use Domain\Product\Repositories\ProductRepository as Repository;

class Create
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $product = new ProductEntity(
            $DTO->name,
            $DTO->price,
            $DTO->description,
            $DTO->category,
            $DTO->imageUrl
        );

        $productExists = $this->repository->findProductByName($product->name());

        if ($productExists) {
            throw new ProductAlreadyExistException("The product $DTO->name already exist");
        }

        $productCreated = $this->repository->create($product);

        return new Response($productCreated);
    }
}
