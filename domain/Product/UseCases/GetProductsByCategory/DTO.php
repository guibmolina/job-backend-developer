<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProductsByCategory;

class DTO
{
    public string $category;

    public function __construct(string $category)
    {
        $this->category = $category;
    }
}
