<?php

namespace App\Http\Controllers;


class SecurityController extends Controller
{
    public function login()
    {
        return view('security.login');
    }
}
