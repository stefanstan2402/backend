<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function allPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Category::query();

        if (!empty($filters['name'])) {
            $query->withName($filters['name']);
        }

        if (!empty($filters['description'])) {
            $query->withDescription($filters['description']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getAll()
    {
        return Category::query()->orderBy('name')->get();
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
