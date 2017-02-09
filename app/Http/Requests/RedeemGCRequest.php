<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RedeemGCRequest extends Request
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
            'barcode' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'barcode.required' => 'Please enter barcode.',
        ];
    }
}
