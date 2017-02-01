<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CustomerRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'first_name'    =>  'required',
            'middle_name'   =>  'required',
            'last_name'     =>  'required',
            'address'       =>  'required',
            'gender'        =>  'required',
            'birthday'      =>  'required|date',
            'email_add'     =>  'required|email|unique:customers',
            'password'      =>  'required|min:6',
        ];
    }
}
