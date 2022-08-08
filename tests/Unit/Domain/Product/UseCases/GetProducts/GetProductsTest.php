<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\GetProducts;

use Domain\Product\List\ProductList;
use Domain\Product\Repositories\BaseRepository;
use Domain\Product\UseCases\GetProducts\GetProducts;
use Domain\Product\UseCases\GetProducts\DTO;
use Domain\Product\UseCases\GetProducts\Response;
use PHPUnit\Framework\TestCase;

class GetProductsTest extends TestCase
{
    private BaseRepository $repository;

    private ProductList $productList;

    private DTO $DTO;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(BaseRepository::class)
        ->onlyMethods([
            'findProductById',
            'findProductsBy',
        ])
        ->getMock();

        $this->DTO = $this->getMockBuilder(DTO::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->productList = $this->getMockBuilder(ProductList::class)->getMock();
    }

    /** @test */
    public function itShouldReturnInstanceOfResponse(): void
    {
        $this->DTO->search = [];

        $this->repository->expects($this->once())
        ->method('findProductsBy')
        ->with($this->DTO->search)
        ->willReturn($this->productList);

        $getProductsUseCase = new GetProducts($this->repository);

        $products = $getProductsUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $products);
    }
}
