<?php

declare(strict_types=1);

namespace Infra\Product\Repositories;

use Domain\Product\Entities\ProductEntity;
use Domain\Product\List\ProductList;
use Domain\Product\Repositories\BaseRepository;
use Illuminate\Support\Facades\Http;

class ProductAPIRepository implements BaseRepository
{
    public function findProductById(int $id): ?ProductEntity
    {
        $url = env('BASE_FAKE_API_PRODUCTS_URL');

        $product = HTTP::get("$url/{$id}")->json();

        if (!$product) {
            return null;
        }

        return new ProductEntity(
            $product['title'],
            $product['price'],
            $product['description'],
            $product['category'],
            $product['image']
        );
    }

    public function findProductsBy(array $searchFilters, ?bool $withImage): ProductList
    {
        $url = env('BASE_FAKE_API_PRODUCTS_URL');

        $products = HTTP::get($url)->json();

        $productList = new ProductList();

        foreach ($products as $product) {
            $productList->add(new ProductEntity(
                $product['title'],
                $product['price'],
                $product['description'],
                $product['category'],
                $product['image']
            ));
        }
        return $productList;
    }
}
