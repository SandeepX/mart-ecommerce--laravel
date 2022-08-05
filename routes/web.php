<?php

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Route;

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


// Route::get('api/paginate-users', function () {
//     $perPage =  request()->get('per_page');
//     return User::paginate($perPage ?? 2);
// });


// Auth::routes();

Route::get('/',function(){
    return redirect()->route('admin.login');
});
Route::get('/user',function(){
    return 'user register from new notification';
})->name('user.verify');

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('single/product/details','HomeController@singleProductDetails');
Route::get('single/preorder/product/details','HomeController@singlePreOrderProductDetails');
