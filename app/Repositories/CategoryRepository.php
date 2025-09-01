<?php

namespace App\Repositories;

use App\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryRepository
{
    private function getQuery()
    {
        return QueryBuilder::for(Category::class)
            ->allowedFilters(['name', 'description'])
            ->allowedSorts('name')
            ->defaultSort('name');
    }

    public function allPaginated(int $perPage = 10)
    {
        return $this->getQuery()
            ->paginate($perPage)
            ->appends(request()->query());
    }


    public function getAll()
    {
        return $this->getQuery()->get();
    }

    public function findWithProducts(int $id)
    {
        return $this->getQuery()
            ->findOrFail($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
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
