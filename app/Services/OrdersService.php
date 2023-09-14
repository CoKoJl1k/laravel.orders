<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsService {


    public function validate(Request $request): array
    {
        $input = $request->only('title', 'description');
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
        ];
        $validator = Validator::make($input, $rules);

        if(!empty($validator->errors()->all())) {
            return ['status' => 'fail','message' => $validator->errors()->all()[0]];
        }
        return  ['status' => 'success'];
    }
}
