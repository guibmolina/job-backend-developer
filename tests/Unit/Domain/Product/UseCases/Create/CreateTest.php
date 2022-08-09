<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\Create;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\Exceptions\ProductAlreadyExistException;
use Domain\Product\Repositories\ProductRepository;
use Domain\Product\UseCases\Create\Create;
use Domain\Product\UseCases\Create\DTO;
use Domain\Product\UseCases\Create\Response;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
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
    public function itShouldTrowAProductAlreadyExistExceptionWhenProductAlreadyExist()
    {
        $this->expectException(ProductAlreadyExistException::class);

        $this->DTO->name = 'product test';
        $this->DTO->price = 11.00;
        $this->DTO->description = 'product description test';
        $this->DTO->category = 'test';
        $this->DTO->imageUrl = null;

        $this->repository->expects($this->once())
        ->method('findProductByName')
        ->with($this->DTO->name)
        ->willReturn($this->entity);

        $createUseCase = new Create($this->repository);

        $createUseCase->execute($this->DTO);
    }

    /** @test */
    public function itShouldReturnInstanceOfResponse()
    {
        $this->DTO->name = 'product test';
        $this->DTO->price = 11.00;
        $this->DTO->description = 'product description test';
        $this->DTO->category = 'test';
        $this->DTO->imageUrl = null;

        $this->repository->expects($this->once())
        ->method('findProductByName')
        ->with($this->DTO->name)
        ->willReturn(null);

        $createUseCase = new Create($this->repository);

        $product = $createUseCase->execute($this->DTO);

        self::assertInstanceOf(Response::class, $product);
    }
}
