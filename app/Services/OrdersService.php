<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersService
{

    public function validateStore(Request $request): array
    {
        $input = $request->only('name', 'email','message');
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if(!empty($validator->errors()->all())) {
            return ['status' => 'fail','message' => $validator->errors()->all()[0]];
        }
        return  ['status' => 'success'];
    }

    public function validateUpdate(Request $request): array
    {
        $input = $request->only('comment');
        $rules = [
            'comment' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if(!empty($validator->errors()->all())) {
            return ['status' => 'fail','message' => $validator->errors()->all()[0]];
        }
        return  ['status' => 'success'];
    }
}
