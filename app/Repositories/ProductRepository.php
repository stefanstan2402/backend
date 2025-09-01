<?php

namespace App\Repositories;

use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ProductRepository
{
    private function getQuery(){
        return QueryBuilder::for(Product::class)
            ->with('category:id,name')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('price'),
                AllowedFilter::exact('stock'),
                AllowedFilter::exact('category_id'),
            ])
            ->latest();
    }

    public function getAll()
    {
        return $this->getQuery()->get();
    }


    /**
     * Get paginated products with filters via Spatie Query Builder
     */
    public function allPaginated(int $perPage = 10)
    {
        return $this->getQuery()
            ->paginate($perPage)
            ->appends(request()->query());
    }

    /**
     * Load product with category
     */
    public function findById(Product $product): Product
    {
        return $product->load('category:id,name');
    }

    /**
     * Create a new product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product
     */
    public function update(array $data, Product $product): Product
    {
        $product->update($data);
        return $product->load('category:id,name');
    }

    /**
     * Delete product
     */
    public function delete(Product $product)
    {
        $product->delete();
    }
}
