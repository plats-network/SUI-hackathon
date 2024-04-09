<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class Likes extends Controller
{
    public function __construct()
    {
         $this->middleware('client_web');
    }

    public function index()
    {
        return view('web.likes');
    }
}
