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
    $posts = Post::latest()->take(6)->Published()->get();
    return view('index', compact('posts'));
}

public function posts()
{
    $posts = Post::latest()->Published()->paginate(2);
    // $categories = Category::take(10)->get();
    return view('posts', compact('posts'));
}

public function post($slug)
{
    $post = Post::where('slug' , $slug)->Published()->first();
    $posts = Post::latest()->take(3)->Published()->get();
    // $categories = Category::take(10)->get();
    return view('post', compact('post' , 'posts'));
}

public function categories()
{
    $categories = Category::all();
    return view('categories', compact('categories'));
}

public function categoryPost($slug)
{
    $category = Category::where('slug' , $slug)->first();
    // $categories = Category::all();
    $posts = $category->posts()->Published()->paginate(2);
    return view('categoryPost' , compact('posts'));

}

public function search(Request $request)
    {
        $this->validate($request, ['search' => 'required|max:255']);
        $search = $request->search;
        $posts = Post::where('title', 'like', "%$search%")->paginate(2);
        $posts->appends(['search' => $search]);

        // $categories = Category::all();
        return view('search', compact('posts', 'search'));
    }

    public function tagPosts($name)
    {
        $query = $name;
        $tags = Tag::where('name', 'like', "%$name%")->paginate(10);
        $tags->appends(['search' => $name]);

        return view('tagPosts', compact('tags', 'query'));
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
}

