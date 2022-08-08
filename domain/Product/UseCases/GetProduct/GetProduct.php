<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProduct;

use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Repositories\BaseRepository;

class GetProduct
{
    private BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $product = $this->repository->findProductById($DTO->id);

        if (!$product) {
            throw new NotFoundProductException("Not found product $DTO->id");
        }

        return new Response($product);
    }
}
