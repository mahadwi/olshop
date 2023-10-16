<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/setLang/{locale}', function ($locale) {
    Session::put('locale', $locale); 
    return back();
})->name('setlang');

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('/users', UserController::class)->except('create', 'show', 'edit');

    Route::resource('/roles', RoleController::class)->except('create', 'show', 'edit');

    Route::resource('/product-category', ProductCategoryController::class)->except('create', 'show', 'edit');
});