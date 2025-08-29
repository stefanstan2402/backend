<?php

namespace App\Repositories;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductRepository
{

    public function allPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Product::with('category:id,name');

        if (!empty($filters['name'])) {
            $query->withName($filters['name']);
        }

        if (!empty($filters['price'])) {
            $query->withPrice($filters['price']);
        }

        if (!empty($filters['stock'])) {
            $query->withStock($filters['stock']);
        }

        if (!empty($filters['category_id'])) {
            $query->withCategory($filters['category_id']);
        }

        return $query->latest()->paginate($perPage);
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
