<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Create;

class DTO
{
    public string $name;

    public float $price;

    public string $category;

    public string $description;

    public ?string $imageUrl;

    public function __construct(
        string $name,
        float $price,
        string $description,
        string $category,
        ?string $imageUrl = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
        $this->imageUrl = $imageUrl;
    }
}
