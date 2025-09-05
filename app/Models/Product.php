<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id','name','price','stock'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // --- Scopes ---
    public function scopeName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopePrice(Builder $query, int $price): Builder
    {
        return $query->where('price', $price);
    }

    public function scopeStock(Builder $query, int $stock): Builder
    {
        return $query->where('stock', $stock);
    }

    public function scopeCategoryId(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
