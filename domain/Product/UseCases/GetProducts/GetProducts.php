<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProducts;

use Domain\Product\Repositories\ProductRepository;

class GetProducts
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $products = $this->repository->findProductsBy($DTO->search);

        return new Response($products);
    }
}
