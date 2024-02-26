<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
        // $admin = Auth::guard('admin')->user();
        // echo 'Welcome'. $admin->name. '<a href="'.route('admin.logout').'">Logout</a>';
    }

    public function logout()
    {
        // If user is not admin he will be looged out from admin panel, this is adminloginCntroller else part code
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
