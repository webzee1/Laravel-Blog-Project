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
    $post = Post::where('slug', $slug)->published()->first();
        // $posts = Post::latest()->take(3)->published()->get();
        // Increase View count
        $postKey = 'post_'.$post->id;
        if(!Session::has($postKey)){
            $post->increment('view_count');
            Session::put($postKey, 1);
        }

        return view('post', compact('post'));
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

    public function likePost($post){
        // Check if user already liked the post or not
        $user = Auth::user();
        $likePost = $user->likedPosts()->where('post_id', $post)->count();
        if($likePost == 0){
            $user->likedPosts()->attach($post);
        } else{
            $user->likedPosts()->detach($post);
        }
        return redirect()->back();
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
}

