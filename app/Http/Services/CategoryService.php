<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function listPaginated(int $perPage = 10)
    {
        return $this->categoryRepository->allPaginated($perPage);
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function show(int $id): Category
    {
        return $this->categoryRepository->findWithProducts($id);
    }

    public function update(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
