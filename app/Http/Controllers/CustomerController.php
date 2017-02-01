<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //


    public function index()
    {
        $customers = Customer::latest('id')->get();

        return view('customer/customer')->with('customers',$customers);
    }

    public function show($id)
    {
        $customers = Customer::findorFail($id);

        return view('customer/show')->with('customers',$customers);
    }

    public function create()
    {
        return view('customer/create');
    }

    public function store(CustomerRequest $request)
    {

        Customer::create($request->all());

        return redirect('customer');
    }

    public function edit($id)
    {

        $customers = Customer::findorFail($id);

        return view('customer/edit')->with('customers',$customers);
    }

    public function update($id, Request $request)
    {
        $customers = Customer::findorFail($id);

        $customers->update($request->all());

        return redirect('customer');
    }
}
