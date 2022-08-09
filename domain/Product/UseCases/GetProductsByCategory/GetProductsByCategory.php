<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProductsByCategory;

use Domain\Product\Repositories\ProductRepository;

class GetProductsByCategory
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $products = $this->repository->findProductsByCategory($DTO->category);

        return new Response($products);
    }
}

