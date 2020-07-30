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

Route::group(['middleware' => ['auth','StoreDatabaseSelection']],function(){
    //User Module Routes
    Route::get('users', 'AllUserController@index' )->name('users');
    Route::get('users/create', 'AllUserController@create' )->name('users.create');
    Route::post('users/store', 'AllUserController@store' )->name('users.store');
    Route::get('users/{iuserid}/edit', 'AllUserController@edit' )->name('users.edit');
    Route::patch('users/update/{iuserid}', 'AllUserController@update' )->name('users.update');
    Route::post('users/remove', 'AllUserController@remove')->name('users.remove');

    //vendor MOdel Route
    Route::get('vendors', 'VendorController@index')->name('vendors');
    Route::get('vendors/create', 'VendorController@create')->name('vendors.create');
    Route::post('vendors/store', 'VendorController@store')->name('vendors.store');
    Route::post('vendors/remove', 'VendorController@remove')->name('vendors.remove');
    Route::get('vendors/{isupplierid}/edit', 'VendorController@edit' )->name('vendors.edit');
    Route::patch('vendors/update/{isupplierid}', 'VendorController@update' )->name('vendors.update');

    //customer Module route
    Route::get('customers', 'CustomerController@index')->name('customers');
    Route::post('customers/remove', 'CustomerController@remove')->name('customers.remove');
    Route::get('customers/create', 'CustomerController@create')->name('customers.create');
    Route::post('customers/store', 'CustomerController@store')->name('customers.store');
    Route::get('customers/{icustomerid}/edit', 'CustomerController@edit' )->name('customers.edit');
    Route::patch('customers/update/{icustomerid}', 'CustomerController@update' )->name('customers.update');

    //Department Module Route
    Route::get('deparments', 'DepartmentController@index')->name('departments');
    Route::post('deparments/store', 'DepartmentController@store')->name('departments.store');

    //Physical Inventroy Module Route
    Route::get('physicalInventroy', 'PhysicalInventroyController@index')->name('physicalInventroy');
    Route::get('physicalInventroy/create', 'PhysicalInventroyController@create')->name('physicalInventroy.create');







});


