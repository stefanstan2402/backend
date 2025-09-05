<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'api_token'];
    protected $hidden = ['password', 'api_token'];

    public function scopeEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    public function scopeApiToken(Builder $query, string $api_token): Builder
    {
        return $query->where('api_token', $api_token);
    }
}

