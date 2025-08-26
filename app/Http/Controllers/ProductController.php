<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->productService->listPaginated();
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request);
        return response()->json($product->load('category:id,name'), 201);
    }

    public function show(Product $product)
    {
        return $this->productService->show($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $this->productService->update($request, $product);
    }

    public function destroy(Product $product)
    {
        return $this->productService->destroy($product);
    }
}
