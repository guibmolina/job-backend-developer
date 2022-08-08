<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\Update;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Repositories\ProductRepository;
use Domain\Product\UseCases\Update\DTO;
use Domain\Product\UseCases\Update\Response;
use Domain\Product\UseCases\Update\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    private ProductRepository $repository;

    private ProductEntity $entity;

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
            'delete',
            'update',
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

        $updateUseCase = new Update($this->repository);

        $updateUseCase->execute($this->DTO);
    }

    /** @test */
    public function itShouldReturnInstanceOfResponse()
    {
        $this->DTO->id = 1;
        $this->DTO->name = 'product test';
        $this->DTO->price = 11.00;
        $this->DTO->description = 'product description test';
        $this->DTO->category = 'test';
        $this->DTO->imageUrl = null;

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn($this->entity);

        $updateUseCase = new Update($this->repository);

        $productUpdated = $updateUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $productUpdated);
    }
}
