<?php

declare(strict_types=1);

namespace Tests\Domain\Product\UseCases\GetProducts;

use Domain\Product\List\ProductList;
use Domain\Product\UseCases\GetProducts\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    private ProductList $productList;

    public function setUp(): void
    {
        parent::setUp();

        $this->productList = $this->getMockBuilder(ProductList::class)
        ->disableOriginalConstructor()
        ->getMock();
    }

    /** @test */
    public function itMustReturnAnArray(): void
    {
        $reponse = new Response($this->productList);

        self::assertIsArray($reponse->response());
    }
}
