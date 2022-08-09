<?php

namespace App\Console\Commands;

use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Exceptions\ProductAlreadyExistException;
use Domain\Product\UseCases\Create\Create;
use Domain\Product\UseCases\Create\DTO as CreateDTO;
use Domain\Product\UseCases\GetProduct\DTO as GetProductDTO;
use Domain\Product\UseCases\GetProduct\GetProduct;
use Domain\Product\UseCases\GetProducts\DTO;
use Domain\Product\UseCases\GetProducts\GetProducts;
use Exception;
use Illuminate\Console\Command;
use Infra\Product\Repositories\ProductAPIRepository;
use Infra\Product\Repositories\ProductDBRepository;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import 
                            {--id= : The ID of the specific product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from external API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->option('id');

        if ($id === "NULL" || !$id) {
            $DTO = new DTO();
    
            $getProductsUseCase = new GetProducts(new ProductAPIRepository());
    
            try {
                $products = $getProductsUseCase->execute($DTO);
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }

            return $this->createProducts($products->response());
        }


        $DTO = new GetProductDTO($id);

        $getProductUseCase = new GetProduct(new ProductAPIRepository());

        try {
            $product = $getProductUseCase->execute($DTO);
        } catch (NotFoundProductException $e) {
            return $this->error($e->getMessage());
        } catch(Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->createProducts([$product->response()]);

    }

    private function createProducts(array $products): void
    {
        $this->withProgressBar($products, function ($product) {
            $DTOCreate = new CreateDTO(
                $product['name'],
                $product['price'],
                $product['description'],
                $product['category'],
                $product['image']
            );

            $createUseCase = new Create(new ProductDBRepository());
            $this->newLine();

            try {
                $createUseCase->execute($DTOCreate);
            } catch(Exception $e) {
                return $this->error($e->getMessage());
            }

            return $this->info("Product {$product['name']} was imported");
        });
    }
}
