<?php

namespace App\Helpers;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Helper
{
    public static function createToken($user): string
    {
        $now = Carbon::now();
        $payload = [
            'sub' => $user->id,
            'iat' => $now->timestamp,
            'exp' => $now->copy()->addDays(30)->timestamp,
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public static function decodeToken($token)
    {
        return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
    }
}
