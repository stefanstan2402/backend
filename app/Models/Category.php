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
    public function scopeWithName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeWithDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'like', '%' . $description . '%');
    }
}
