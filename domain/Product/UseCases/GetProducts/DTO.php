<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProducts;

class DTO
{
    public array $search;

    public function __construct(?array $search = [])
    {
        $this->search = $search;
    }
}
