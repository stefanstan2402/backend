<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'api_token'];
    protected $hidden = ['password', 'api_token'];
}

