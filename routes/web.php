<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\DashboardController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//////////////////////////////////// Admin /////////////////////////////////////////////////

Route::group(['as' => 'admin.' , 'prefix' => 'admin' , 'namespace' => 'Admin' , 'middleware' => ['auth' , 'admin']],
    function () {

        Route::get('dashboard' , [DashboardController::class, 'index' ])->name('dashboard');
    
    });




//////////////////////////////////// User /////////////////////////////////////////////////

Route::group(['as' => 'user.' , 'prefix' => 'user' , 'namespace' => 'User' , 'middleware' => ['auth' , 'user']],
    function () {

        Route::get('dashboard' , [DashboardController::class, 'index' ])->name('dashboard');
    
    });


    
