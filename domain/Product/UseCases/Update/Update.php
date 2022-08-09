<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Update;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Exceptions\ProductAlreadyExistException;
use Domain\Product\Repositories\ProductRepository as Repository;

class Update
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $product = $this->repository->findProductById($DTO->id);

        if (!$product) {
            throw new NotFoundProductException("Not Found product $DTO->id");
        }

        $productSameName = $this->repository->findProductByName($DTO->name);

        if ($productSameName) {
            throw new ProductAlreadyExistException("The product $DTO->name already exist");
        }

        $newProduct = new ProductEntity(
            $DTO->name,
            $DTO->price,
            $DTO->description,
            $DTO->category,
            $DTO->imageUrl
        );

        $productUpdated = $this->repository->update($product->id(), $newProduct);

        return new Response($productUpdated);
    }
}
