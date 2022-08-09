<?php

declare(strict_types=1);

namespace Domain\Product\UseCases\GetProductsByCategory;

use Domain\Product\List\ProductList;

class Response
{
    private ProductList $products;

    public function __construct(ProductList $products)
    {
        $this->products = $products;
    }

    public function response(): array
    {
        $response = [];

        foreach ($this->products as $product) {
            $response[] = [
                'id' => $product->id(),
                'name' => $product->name(),
                'price' => $product->price(),
                'category' => $product->category(),
                'description' => $product->description(),
                'image' => $product->imageUrl(),
            ];
        }
        return $response;
    }
}
