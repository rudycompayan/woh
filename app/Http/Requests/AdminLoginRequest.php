<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdminLoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field  is required.',
            'password.required' => 'The password field is required.',
        ];
    }
}
