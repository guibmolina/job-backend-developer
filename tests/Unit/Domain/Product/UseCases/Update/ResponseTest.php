<?php

declare(strict_types=1);

namespace Tests\Domain\Unit\Product\UseCases\Update;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\UseCases\Update\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    private ProductEntity $entity;

    public function setUp(): void
    {
        parent::setUp();

        $this->entity = $this->getMockBuilder(ProductEntity::class)
        ->disableOriginalConstructor()
        ->getMock();
    }

    /** @test */
    public function itMustReturnAnArray(): void
    {
        $reponse = new Response($this->entity);

        self::assertIsArray($reponse->response());
        self::assertArrayHasKey('id', $reponse->response());
    }
}
