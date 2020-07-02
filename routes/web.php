<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::post('/dashboard', 'HomeController@dashContent')->name('dashboard');

Route::group(['middleware' => ['StoreDatabaseSelection']],function(){
    Route::get('users', 'AllUserController@index' )->name('users');
    Route::get('users/create', 'AllUserController@create' )->name('users.create');



});
Route::post('users/store', 'AllUserController@store' )->name('users.store');

// Route::resource('users', 'AllUserController');

