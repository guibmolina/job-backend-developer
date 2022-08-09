<?php

declare(strict_types=1);

namespace Infra\Product\Repositories;

use App\Models\Product;
use Domain\Product\Entities\ProductEntity;
use Domain\Product\List\ProductList;
use Domain\Product\Repositories\ProductRepository;
use Exception;

class ProductDBRepository implements ProductRepository
{
    public function create(ProductEntity $entity): ProductEntity
    {
        $product = new Product();
        $product->name = $entity->name();
        $product->price = $entity->price();
        $product->description = $entity->description();
        $product->category = $entity->category();
        $product->image = $entity->imageUrl();
        
        try {
            $product->save();
        } catch (Exception $e) {
            throw new Exception("Error to save product: {$entity->name()}");
        }

        return $this->mapProductEntityDomain($product);
    }

    public function findProductById(int $id): ?ProductEntity
    {
        $product = Product::find($id);

        if (!$product) {
            return null;
        }

        return $this->mapProductEntityDomain($product);

    }

    public function findProductByName(string $name): ?ProductEntity
    {
        $product = Product::where('name', $name)->first();

        if (!$product) {
            return null;
        }

        return $this->mapProductEntityDomain($product);
    }

    public function findProductsBy(array $searchFilters, ?bool $withImage): ProductList
    {
        $query = Product::select();

        foreach ($searchFilters as $column => $word) {
            $query->where($column, $word);
        }

        if (!is_null($withImage)) {
            $query->when($withImage, function ($query) {
                $query->whereNotNull('image');
            }, function($query) {
                $query->whereNull('image');
            });
        }

        $products = $query->get();

        $productList = new ProductList();

        foreach ($products as $product) {
            $productList->add($this->mapProductEntityDomain($product));
        }

        return $productList;
    }

    public function findProductsByCategory(string $category): ProductList
    {
        $products = Product::where('category', $category)->get();

        $productList = new ProductList();

        foreach ($products as $product) {
            $productList->add($this->mapProductEntityDomain($product));
        }

        return $productList;
    }

    public function delete(ProductEntity $entity): void
    {
        $product = Product::find($entity->id());

        try {
            $product->delete();
        } catch (Exception $e) {
            throw new Exception("Error to delete product {$entity->id()}");
        }

        return;
    }

    public function update(int $productId, ProductEntity $entity): ProductEntity
    {
        $product = Product::find($productId);

        $product->name = $entity->name();
        $product->price = $entity->price();
        $product->description = $entity->description();
        $product->category = $entity->category();
        $product->image = $entity->imageUrl();

        try {
            $product->save();
        } catch (Exception $e) {
            throw new Exception("Error to update product $productId");
        }

        return $this->mapProductEntityDomain($product);
    }

    private function mapProductEntityDomain(Product $product): ProductEntity
    {
        $newProduct = new ProductEntity(
            $product->name,
            $product->price,
            $product->description,
            $product->category,
            $product->image
        );

        $newProduct->setId($product->id);

        return $newProduct;
    }
}
