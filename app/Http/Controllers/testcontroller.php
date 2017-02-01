<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class testcontroller extends Controller
{
    public function site()
    {
        $data = array("fname"=>"Rudy", "lname"=>"Compayan");
        return view('site/site',$data);
    }

}
