<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return $this->categoryService->listPaginated(10);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return $this->categoryService->show($category->id);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return $this->categoryService->update($category, $request->validated());
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
        return response()->noContent();
    }
}
