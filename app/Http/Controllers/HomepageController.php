<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        return view('main_page.home_page');
    }

    public function contact_page()
    {
        return view('main_page.contact_page');
    }

    public function gallery_page()
    {
        return view('main_page.gallery_page');
    }

    public function about_page()
    {
        return view('main_page.about_page');
    }
}
