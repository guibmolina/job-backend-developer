<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\GetProductsByCategory;

use Domain\Product\List\ProductList;
use Domain\Product\Repositories\ProductRepository;
use Domain\Product\UseCases\GetProductsByCategory\Response;
use Domain\Product\UseCases\GetProductsByCategory\DTO;
use Domain\Product\UseCases\GetProductsByCategory\GetProductsByCategory;
use PHPUnit\Framework\TestCase;

class GetProductsByCategoryTest extends TestCase
{
    private ProductRepository $repository;

    private ProductList $productList;

    private DTO $DTO;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ProductRepository::class)
        ->onlyMethods([
            'create',
            'findProductById',
            'findProductByName',
            'findProductsBy',
            'findProductsByCategory',
            'delete',
            'update',
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
        $this->DTO->category = 'test';

        $this->repository->expects($this->once())
        ->method('findProductsByCategory')
        ->with($this->DTO->category)
        ->willReturn($this->productList);

        $getProductsUseCase = new GetProductsByCategory($this->repository);

        $products = $getProductsUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $products);
    }
}
