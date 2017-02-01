@extends('dashboard')



@section('content')

    <h1>Edit Customer</h1>
    <hr>

    {{ Form::model($customers,array('url' => "/customer/{$customers->id}", 'method' => 'PATCH')) }}

    <div class="form-group">
        {{ Form::label("First Name","First Name") }}
        {{ Form::text("first_name",null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Middle Name","Middle Name") }}
        {{ Form::text("middle_name",null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Last Name","Last Name") }}
        {{ Form::text("last_name",null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Address","Address") }}
        {{ Form::text("address",null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Gender","Gender") }}
        {{ Form::select('gender', array('male' => 'Male', 'female' => 'Female'),null,array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Birthday","Birthday") }}
        {{ Form::text("birthday",null, array('class' => 'form-control','placeholder'=>'YYYY-MM-DD')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Email Address","Email Address") }}
        {{ Form::text("email_add",null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label("Password","Password") }}
        {{ Form::password("password",array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::submit('Save',array('class' => 'btn btn-primary form-control')) }}

    </div>

    {{ Form::close() }}

    @if (isset($errors) && $errors->any())
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger alert-alt">
                    <strong><i class="fa fa-bug fa-fw"></i>Ops!</strong><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <br/>
    @endif

@stop