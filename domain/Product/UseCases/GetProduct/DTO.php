<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProduct;

class DTO
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
