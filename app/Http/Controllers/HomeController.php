<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    
    public function index()
{
    $posts = Post::latest()->take(6)->get();
    return view('index', compact('posts'));
}

public function posts()
{
    $posts = Post::latest()->paginate(2);
    $categories = Category::take(10)->get();
    return view('posts', compact('posts' , 'categories'));
}

public function categories()
{
    $categories = Category::all();
    return view('categories', compact('categories'));
}

public function post($slug)
{
    $post = Post::where('slug' , $slug)->first();
    $posts = Post::latest()->take(3)->get();
    $categories = Category::take(10)->get();
    return view('post', compact('post' , 'categories' , 'posts'));
}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
}

