<?php

declare(strict_types=1);

namespace Domain\Product\Entities;

class ProductEntity
{
    private int $id;

    private string $name;

    private float $price;

    private string $category;

    private string $description;

    private ?string $imageUrl = null;

    public function __construct(
        string $name,
        float $price,
        string $description,
        string $category,
        ?string $imageUrl
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
        $this->imageUrl = $imageUrl;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function imageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
