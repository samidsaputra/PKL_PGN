<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class requestController extends Controller
{
    // public function dashboard(){
    //     return view('Requester.dashboard');
    // }

    public function request(){
        return view('Requester.request');
    }
}
