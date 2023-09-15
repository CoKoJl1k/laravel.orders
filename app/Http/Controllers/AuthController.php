<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JWTService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    private UserService $userService;
    private JWTService $jwtService;

    public function __construct(UserService $userService, JWTService $jwtService)
    {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function register(Request $request)
    {
        $errors = $this->userService->validateRegister($request);
        if(!empty($errors['message'])) {
            return response()->json(['status' => 'fail', 'message' => $errors['message']],400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $payload['user_id'] = $user->id;
        $payload['name'] = $user->name;
        $payload['email'] = $user->email;

        $token = $this->jwtService->generateToken($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function login(Request $request)
    {
        $errors = $this->userService->validateLogin($request);
        if(!empty($errors['message'])) {
            return response()->json(['status' => 'fail', 'message' => $errors['message']]);
        }

        $input = $request->only('email', 'password');
        $check = Auth::attempt($input);
        if (empty($check)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized',], 401);
        }

        $user = Auth::user();
        $payload['user_id'] = $user->id;
        $payload['name'] = $user->name;
        $payload['email'] = $user->email;
        $token = $this->jwtService->generateToken($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'User longin successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}
