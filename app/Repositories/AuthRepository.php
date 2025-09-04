<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AuthRepository
{
    private function getQuery(): QueryBuilder
    {
        return QueryBuilder::for(User::class)
            ->defaultSort('-id');
    }

    public function getByEmail(string $email)
    {
        return $this->getQuery()->where('email', $email)->first();
    }

    public function getByApiToken(Request $request)
    {
        return $this->getQuery()->where('api_token', $request->bearerToken())->first();
    }
}
