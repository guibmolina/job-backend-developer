<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProducts;

class DTO
{
    public ?array $search;

    public ?bool $withImage;

    public function __construct(?array $search = null, ?bool $withImage = null)
    {
        $this->search = $search ?? [];

        $this->withImage = $withImage;
    }
}
