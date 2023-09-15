<?php
namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class JWTService
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET');
    }

    /**
     * @param array $payload
     * @param int $expiration
     * @return string
     */

    public function generateToken(array $payload, int $expiration = 36000)
    {
        $payload['created'] = time();
        $payload['expire'] = time() + $expiration;
        return JWT::encode($payload, $this->secretKey,'HS256');
    }

    /**
     * @param string $token
     * @return object
     */

    public function decodeToken(string $token)
    {
        return JWT::decode($token, new Key($this->secretKey, 'HS256'));
    }

    /**
     * @param Request $request
     * @return array|string|null
     */

    public function getToken(Request $request)
    {
        return str_replace('Bearer ', '', $request->header('Authorization'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getUserByToken(Request $request)
    {
        try {
            $token = $this->getToken($request);
            $decodedToken = $this->decodeToken($token);
            $user_id = $decodedToken->user_id;
            return User::findOrFail($user_id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}
