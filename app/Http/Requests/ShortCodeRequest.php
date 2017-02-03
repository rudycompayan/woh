<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ShortCodeRequest extends Request
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
            'type' => 'required',
            'max_no' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Please select at least one code to generate.',
            'max_no.required' => 'The maximum number field is required.',
        ];
    }
}
