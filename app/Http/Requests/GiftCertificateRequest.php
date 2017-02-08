<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GiftCertificateRequest extends Request
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
            'gc_to' => 'required',
            'gc_name' => 'required',
            'gc_description' => 'required',
            'gc_amount' => 'required',
            'gc_number' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'gc_to.required' => 'GC To is required.',
            'gc_name.required' => 'GC Name is required.',
            'gc_description.required' => 'GC Description is required.',
            'gc_amount.required' => 'GC Amount is required.',
            'gc_number.required' => 'Please enter how many GC you want to generate.',
        ];
    }
}
