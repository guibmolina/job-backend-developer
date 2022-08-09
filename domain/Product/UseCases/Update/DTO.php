<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\Update;

class DTO
{
    public int $id;

    public string $name;

    public float $price;

    public string $category;

    public string $description;

    public ?string $imageUrl;

    public function __construct(
        int $id,
        string $name,
        float $price,
        string $description,
        string $category,
        ?string $imageUrl = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
        $this->imageUrl = $imageUrl;
    }
}
