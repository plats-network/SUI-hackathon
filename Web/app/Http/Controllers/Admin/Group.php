<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Group extends  Controller
{
    public function __construct()
    {
        $this->middleware('client_admin');
    }

    public function index(Request $request)
    {
        return view(
            'admin.group.index'
        );
    }
}
