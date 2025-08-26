<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function allPaginated(int $perPage = 10)
    {
        return Category::query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function findWithProducts(int $id): Category
    {
        return Category::withCount('products')->findOrFail($id);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
