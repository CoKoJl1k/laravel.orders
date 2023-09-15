<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserService {

    protected array $input;

    public function  __construct(Request $request)
    {
        $this->input = $request->only('email', 'password','name');
    }

    public function validateLogin(Request $request): array
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ];
        $validator = Validator::make($this->input, $rules);

        if (!empty($validator->errors()->all())) {
            return ['status' => 'fail','message' => $validator->errors()->all()[0]];
        }
        return  ['status' => 'success'];
    }

    public function validateRegister(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:255',
        ];
        $validator = Validator::make($this->input, $rules);

        if(!empty($validator->errors()->all())) {
            return ['status' => 'fail','message' => $validator->errors()->all()[0]];
        }
        return  ['status' => 'success'];
    }
}
