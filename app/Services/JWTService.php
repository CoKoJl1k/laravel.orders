<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;


class JWTService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET');
    }

    public function generateToken(array $payload, int $expiration = 36000): string
    {
        $payload['created'] = time();
        $payload['expire'] = time() + $expiration;
        return JWT::encode($payload, $this->secretKey,'HS256');
    }

    public function decodeToken(string $token): object
    {
        return JWT::decode($token, new Key($this->secretKey, 'HS256'));
    }

    public function getToken(Request $request)
    {
        return str_replace('Bearer ', '', $request->header('Authorization'));
    }
}
