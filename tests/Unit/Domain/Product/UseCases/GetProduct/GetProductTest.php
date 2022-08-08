<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\GetProduct;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Repositories\BaseRepository;
use Domain\Product\UseCases\GetProduct\DTO;
use Domain\Product\UseCases\GetProduct\GetProduct;
use Domain\Product\UseCases\GetProduct\Response;
use PHPUnit\Framework\TestCase;

class GetProductTest extends TestCase
{
    private BaseRepository $repository;

    private ProductEntity $entity;

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

        $this->entity = $this->getMockBuilder(ProductEntity::class)
        ->disableOriginalConstructor()
        ->getMock();
    }

    /** @test */
    public function itShouldTrowNotFoundProductExceptionWhenProductNotExists(): void
    {
        $this->expectException(NotFoundProductException::class);

        $this->DTO->id = 1;

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn(null);

        $getProductUseCase = new GetProduct($this->repository);

        $getProductUseCase->execute($this->DTO);
    }

    /** @test */
    public function itShouldReturnInstanceOfResponse(): void
    {
        $this->DTO->id = 1;

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn($this->entity);

        $getProductUseCase = new GetProduct($this->repository);

        $product = $getProductUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $product);
    }
}
