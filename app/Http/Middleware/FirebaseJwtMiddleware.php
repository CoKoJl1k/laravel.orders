<?php
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class FirebaseJwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }
        try {
            $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->merge(['user_id' => $decodedToken->user_id]);
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        return $next($request);
    }
}
