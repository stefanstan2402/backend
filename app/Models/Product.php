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
    public function scopeWithName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeWithPrice(Builder $query, string $price): Builder
    {
        return $query->where('price', $price);
    }

    public function scopeWithStock(Builder $query, string $stock): Builder
    {
        return $query->where('stock', $stock);
    }

    public function scopeWithCategory(Builder $query, string $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
