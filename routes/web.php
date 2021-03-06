<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Mail\NewPost;   




use App\Http\Controllers\User\UserDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/admin/users', function () {
//     return view('admin.users.index');
// });

Auth::routes();

//Social Login
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class , 'redirectToProvider']);
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class , 'handleProviderCallback']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [HomeController::class, 'posts'])->name('posts');
Route::get('/post/{slug}', [HomeController::class, 'post'])->name('post');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/category/{slug}', [HomeController::class, 'categoryPost'])->name('category.post');
Route::get('/search' , [HomeController::class, 'search'])->name('search');
Route::get('/tag/{name}', [HomeController::class, 'tagPosts'])->name('tag.posts');
Route::post('/like-post/{post}', [HomeController::class, 'likePost'])->name('post.like')->middleware('auth');

Route::post('/comment/{post}', [CommentController::class, 'store'])->name('comment.store')->middleware('auth');
Route::post('/comment-reply/{comment}', [CommentReplyController::class, 'store'])->name('reply.store')->middleware('auth');






//////////////////////////////////// Admin /////////////////////////////////////////////////
// ['as' => 'admin.' , 'prefix' => 'admin' , 'namespace' => 'Admin' , 'middleware' => ['auth' , 'admin']





Route::group(['as' => 'admin.' , 'prefix' => 'admin' , 'middleware' => ['auth' , 'admin']],
    function () {

        Route::get('dashboard' , [DashboardController::class, 'index' ])->name('dashboard');
        Route::get('profile' , [DashboardController::class, 'showProfile' ])->name('profile');
        Route::put('profile' , [DashboardController::class, 'updateProfile' ])->name('profile.update');
        Route::put('profile/password' , [DashboardController::class, 'changePassword' ])->name('profile.password');

        Route::resource('user' , UserController::class)->except(['create' , 'show' , 'edit' , 'store']);
        Route::resource('category' , CategoryController::class)->except(['create' , 'show' , 'edit']);
        Route::resource('post' , PostController::class);

        Route::get('/comments' , [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comment.index');
        Route::delete('/comment/{id}' , [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comment.destroy');
        
        Route::get('/reply-comments' , [App\Http\Controllers\Admin\CommentReplyController::class, 'index'])->name('comment-reply.index');
        Route::delete('/reply-comment/{id}' , [App\Http\Controllers\Admin\CommentReplyController::class, 'destroy'])->name('comment-reply.destroy');
        
        Route::get('/post-liked-users/{post}' , [PostController::class, 'LikedUsers'])->name('post.like.users');

    });




//////////////////////////////////// User /////////////////////////////////////////////////

Route::group(['as' => 'user.' , 'prefix' => 'user' ,  'middleware' => ['auth' , 'user']],
    function () {

        Route::get('dashboard' , [UserDashboardController::class, 'index' ])->name('dashboard');

        Route::get('profile' , [UserDashboardController::class, 'showProfile' ])->name('profile');
        Route::put('profile' , [UserDashboardController::class, 'updateProfile' ])->name('profile.update');
        Route::put('profile/password' , [UserDashboardController::class, 'changePassword' ])->name('profile.password');

        Route::get('comments' , [App\Http\Controllers\User\CommentController::class, 'index' ])->name('comment.index');
        Route::delete('/comment/{id}' , [App\Http\Controllers\User\CommentController::class, 'destroy'])->name('comment.destroy');

        Route::get('/reply-comments' , [App\Http\Controllers\User\CommentReplyController::class, 'index'])->name('reply-comment.index');
        Route::delete('/reply-comment/{id}' , [App\Http\Controllers\User\CommentReplyController::class, 'destroy'])->name('reply-comment.destroy');
        
        Route::get('/user-liked-posts' , [UserDashboardController::class, 'likedposts' ])->name('like.posts');
    });



// View Composer
View::composer('layouts.frontend.partials.sidebar', function ($view) {
    $categories = Category::all()->take(10);
    $recentPosts = Post::latest()->take(3)->get();
    $recentTags = Tag::all();
    return $view->with('categories', $categories)->with('recentPosts', $recentPosts)->with('recentTags', $recentTags);
    
}); 


// Send Mail
Route::get('/send' , function(){
    $post = Post::findOrFail(14);

    Mail::to('user@user.com')
    ->bcc(['user1@user.com' , 'user2@user.com'])
    ->queue(new NewPost($post));
    return (new App\Mail\NewPost($post))->render();
});


    
