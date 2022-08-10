<?php

namespace Tests\Domain\Unit\Product\Lists;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\List\ProductList;
use PHPUnit\Framework\TestCase;
use Traversable;

class ListTest extends TestCase
{
    private ProductEntity $product;

    private ProductList $productList;

    public function setUp(): void
    {
        parent::setUp();

        $this->product = $this->getMockBuilder(ProductEntity::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->productList = new ProductList();

        $this->productList->add($this->product);
    }

    /** @test */
    public function itMustReturnAnArrayOfProducts(): void
    {
        self::assertIsArray($this->productList->products());
        self::assertInstanceOf(ProductEntity::class, $this->productList->products()[0]);
    }

    /** @test */
    public function itMustReturnAnIterator(): void
    {
        self::assertInstanceOf(Traversable::class, $this->productList->getIterator());
    }
}
