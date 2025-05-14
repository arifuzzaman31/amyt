<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // return "hello";
        return view('pages.customer.customer');
    }

    public function store(Request $req)
    {
        return view('pages.customer.customer');
    }
}
