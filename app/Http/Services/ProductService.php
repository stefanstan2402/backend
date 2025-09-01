<?php

namespace App\Http\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function listPaginated(int $perPage = 10)
    {
        return $this->productRepository->allPaginated($perPage);
    }

    public function listAll()
    {
        return $this->productRepository->getAll();
    }

    public function create(StoreProductRequest $request)
    {
        return $this->productRepository->create($request->validated());
    }

    public function show(Product $product): Product
    {
        return $this->productRepository->findById($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $this->productRepository->update($request, $product);
    }

    public function destroy(Product $product)
    {
        return $this->productRepository->delete($product);
    }
}
