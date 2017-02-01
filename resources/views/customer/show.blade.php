@extends('app')



@section('content')

    <h1>Customers</h1>
    <hr>
    <table>
        <tr>
            <th>Name:</th><td>{{$customers->first_name}} {{$customers->middle_name}} {{$customers->last_name}}</td>
        </tr>
        <tr>
            <th>Address:</th><td>{{$customers->address}}</td>
        </tr>
        <tr>
            <th>Birthday:</th><td>{{$customers->birthday}}</td>
        </tr>
        <tr>
            <th>Gender:</th><td>{{$customers->gender}}</td>
        </tr>
        <tr>
            <th>Email Address:</th><td>{{$customers->email_add}}</td>
        </tr>
        <tr>

    </table>

@stop