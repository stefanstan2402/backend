<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'like', '%' . $description . '%');
    }
}
