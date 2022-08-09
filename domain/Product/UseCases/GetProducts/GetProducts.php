<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProducts;

use Domain\Product\Repositories\BaseRepository;

class GetProducts
{
    private BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $products = $this->repository->findProductsBy($DTO->search, $DTO->withImage);

        return new Response($products);
    }
}
