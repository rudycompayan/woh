@extends('dashboard')



@section('content')

    <style type="text/css">
        table{
            border:1px solid lightgray;
        }
        th{
            cellspacing:5px !important;
            text-align:left;
            font-weight: bold;
        }
    </style>

<h1>Customers</h1>
<hr>
<a href="{{ url('/customer/create') }}" style="font-size:24px" title="Add New">+ Add New</a>
<table class="table table-hover">
    <tr class="info">
        <th>Name</th>
        <th>Address</th>
        <th>Birthday</th>
        <th>Gender</th>
        <th>Email Address</th>
    </tr>
    @foreach($customers as $customer)

        <tr>
            <td><a href="{{ url("/customer/{$customer->id}/edit") }}"><span class="glyphicons glyphicons-user"></span>{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</a></td>
            <td>{{$customer->address}}</td>
            <td>{{\Carbon\Carbon::parse($customer->birthday)->format('F d, Y')}}</td>
            <td>{{$customer->gender}}</td>
            <td>{{$customer->email_add}}</td>
        </tr>

    @endforeach
    <tr>
        <td colspan="5">Records Found: <b>{{count($customers)}}</b></td>
    </tr>
</table>

@stop