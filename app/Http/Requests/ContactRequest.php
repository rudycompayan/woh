<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactRequest extends Request
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
            'from_name' => 'required',
            'from_email' => 'required',
            'number' => 'required',
            'message' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'from_name.required' => 'Please enter your name.',
            'from_email.required' => 'Please enter your email.',
            'number.required' => 'Please enter your phone number.',
            'message.required' => 'Please enter your message.',
        ];
    }
}
