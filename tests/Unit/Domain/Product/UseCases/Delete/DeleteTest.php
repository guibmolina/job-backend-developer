<?php

declare(strict_types=1);

namespace Tests\Domain\Unit\Product\UseCases\Delete;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Repositories\ProductRepository;
use Domain\Product\UseCases\Delete\Delete;
use Domain\Product\UseCases\Delete\DTO;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
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

        $deleteUseCase = new Delete($this->repository);

        $deleteUseCase->execute($this->DTO);
    }

    /** @test */
    public function itMustDeleteAProduct(): void
    {
        $this->DTO->id = 1;

        $this->repository->expects($this->once())
        ->method('findProductById')
        ->with($this->DTO->id)
        ->willReturn($this->entity);

        $deleteUseCase = new Delete($this->repository);

        $deleteUseCase->execute($this->DTO);
    }
}
