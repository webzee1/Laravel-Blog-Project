<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;




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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/posts', [App\Http\Controllers\HomeController::class, 'posts'])->name('posts');
Route::get('/post/{slug}', [App\Http\Controllers\HomeController::class, 'post'])->name('post');
Route::get('/categories', [App\Http\Controllers\HomeController::class, 'categories'])->name('categories');
Route::get('/category/{slug}', [App\Http\Controllers\HomeController::class, 'categoryPost'])->name('category.post');
Route::get('/search' , [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::get('/tag/{name}', [App\Http\Controllers\HomeController::class, 'tagPosts'])->name('tag.posts');


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
    
    });




//////////////////////////////////// User /////////////////////////////////////////////////

Route::group(['as' => 'user.' , 'prefix' => 'user' , 'namespace' => 'User' , 'middleware' => ['auth' , 'user']],
    function () {

        Route::get('dashboard' , [UserDashboardController::class, 'index' ])->name('dashboard');
    
    });



// View Composer
View::composer('layouts.frontend.partials.sidebar', function ($view) {
    $categories = Category::all()->take(10);
    $recentPosts = Post::latest()->take(3)->get();
    $recentTags = Tag::all();
    return $view->with('categories', $categories)->with('recentPosts', $recentPosts)->with('recentTags', $recentTags);
});    


    
