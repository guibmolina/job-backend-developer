<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Update;

use Domain\Product\Entities\ProductEntity;

class Response
{
    private ProductEntity $entity;

    public function __construct(ProductEntity $entity)
    {
        $this->entity = $entity;
    }

    public function response(): array
    {
        return [
            'id' => $this->entity->id(),
        ];
    }
}
