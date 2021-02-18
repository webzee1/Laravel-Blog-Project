<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;





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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/admin/users', function () {
//     return view('admin.users.index');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//////////////////////////////////// Admin /////////////////////////////////////////////////
// ['as' => 'admin.' , 'prefix' => 'admin' , 'namespace' => 'Admin' , 'middleware' => ['auth' , 'admin']





Route::group(['as' => 'admin.' , 'prefix' => 'admin' , 'middleware' => ['auth' , 'admin']],
    function () {

        Route::get('dashboard' , [DashboardController::class, 'index' ])->name('dashboard');
        Route::resource('user' , UserController::class)->except(['create' , 'show' , 'edit' , 'store']);
        Route::resource('category' , CategoryController::class)->except(['create' , 'show' , 'edit']);
    
    });




//////////////////////////////////// User /////////////////////////////////////////////////

Route::group(['as' => 'user.' , 'prefix' => 'user' , 'namespace' => 'User' , 'middleware' => ['auth' , 'user']],
    function () {

        Route::get('dashboard' , [UserDashboardController::class, 'index' ])->name('dashboard');
    
    });


    
