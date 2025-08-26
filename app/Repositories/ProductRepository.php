<?php

namespace App\Repositories;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductRepository
{

    public function allPaginated(int $perPage = 10)
    {
        return Product::with('category:id,name')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(Product $product): Product
    {
        return $product->load('category:id,name');
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(UpdateProductRequest $request, Product $product): Product
    {
        $product->update($request->validated());
        return $product->load('category:id,name');
    }

    public function delete(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
