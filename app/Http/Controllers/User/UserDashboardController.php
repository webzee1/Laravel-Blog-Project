<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function likedPosts(){
        return view('user.likedPosts');
     }
}
