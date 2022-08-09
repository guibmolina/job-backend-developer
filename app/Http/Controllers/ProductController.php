<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Domain\Product\Exceptions\NotFoundProductException;
use Domain\Product\Exceptions\ProductAlreadyExistException;
use Domain\Product\UseCases\Create\Create;
use Domain\Product\UseCases\Create\DTO as CreateDTO;
use Domain\Product\UseCases\Delete\Delete;
use Domain\Product\UseCases\Delete\DTO as DeleteDTO;
use Domain\Product\UseCases\GetProduct\DTO;
use Domain\Product\UseCases\GetProduct\GetProduct;
use Domain\Product\UseCases\GetProducts\DTO as GetProductsDTO;
use Domain\Product\UseCases\GetProducts\GetProducts;
use Domain\Product\UseCases\GetProductsByCategory\DTO as GetProductsByCategoryDTO;
use Domain\Product\UseCases\GetProductsByCategory\GetProductsByCategory;
use Domain\Product\UseCases\Update\DTO as UpdateDTO;
use Domain\Product\UseCases\Update\Update;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Product\Repositories\ProductDBRepository;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $withImage = is_null($request->query('withImage')) ? null : $request->boolean('withImage') ; 

        $DTO = new GetProductsDTO($request->query('search'), $withImage);

        $getProductsUseCase = new GetProducts(new ProductDBRepository());

        try {
            $products = $getProductsUseCase->execute($DTO);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($products->response());
    }

    public function show(int $id): JsonResponse
    {
        $DTO = new DTO($id);

        $getProductUseCase = new GetProduct(new ProductDBRepository());
        
        try {
            $product = $getProductUseCase->execute($DTO);
        } catch (NotFoundProductException $e) {
            return response()->json([$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($product->response());
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $request->validated();


        $DTO = new CreateDTO(
            $request->name,
            $request->price,
            $request->description,
            $request->category,
            $request->image
        );

        $createUseCase = new Create(new ProductDBRepository());

        try {
            $product = $createUseCase->execute($DTO);
        } catch (ProductAlreadyExistException $e) {
            return response()->json([$e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($product->response());
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $request->validated();

        $DTO = new UpdateDTO(
            $id,
            $request->name,
            $request->price,
            $request->description,
            $request->category,
            $request->image
        );

        $updateUseCase = new Update(new ProductDBRepository());

        try {
            $product = $updateUseCase->execute($DTO);
        } catch (NotFoundProductException $e) {
            return response()->json([$e->getMessage()], 404);
        } catch (ProductAlreadyExistException $e) {
            return response()->json([$e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($product->response());
    }

    public function destroy(int $id): JsonResponse
    {
        $DTO = new DeleteDTO($id);

        $deleteUseCase = new Delete(new ProductDBRepository());

        try {
            $deleteUseCase->execute($DTO);
        } catch (NotFoundProductException $e) {
            return response()->json([$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return  response()->json([], 201);
    }

    public function indexByCategory(string $category): JsonResponse
    {
        $DTO = new GetProductsByCategoryDTO($category);

        $getProductsByCategoryUseCase = new GetProductsByCategory(new ProductDBRepository);

        try {
            $products = $getProductsByCategoryUseCase->execute($DTO);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($products->response());
    }
}
