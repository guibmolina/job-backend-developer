<?php

declare(strict_types=1);

namespace Tests\Domain\Unit\Product\UseCases\Update;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Exceptions\ProductAlreadyExistException;
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
            'findProductsByCategory',
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
    public function itShouldTrowAProductAlreadyExistExceptionWhenProductAlreadyExist()
    {
        $this->expectException(ProductAlreadyExistException::class);

        $this->DTO->id = 1;
        $this->DTO->name = 'Test';

        $this->repository->expects($this->once())
        ->method('findProductByName')
        ->with($this->DTO->name)
        ->willReturn($this->entity);

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn($this->entity);

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

        $this->entity->expects($this->once())
        ->method('id')
        ->willReturn($this->DTO->id);

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn($this->entity);

        $updateUseCase = new Update($this->repository);

        $productUpdated = $updateUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $productUpdated);
    }
}
