<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProduct;

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
            'name' => $this->entity->name(),
            'price' => $this->entity->price(),
            'description' => $this->entity->description(),
            'category' => $this->entity->category(),
            'image' => $this->entity->imageUrl()
        ];
    }
}
