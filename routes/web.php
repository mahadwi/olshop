<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GroupCoaController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;

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

    Route::resource('/color', ColorController::class)->except('create', 'show', 'edit');

    Route::resource('/product-category', ProductCategoryController::class)->except('create', 'show', 'edit');

    Route::resource('/group-coa', GroupCoaController::class)->except('create', 'show', 'edit');
    
    Route::resource('/coa', CoaController::class)->except('create', 'show', 'edit');

    Route::resource('/vendor', VendorController::class)->except('create', 'show', 'edit');

    Route::resource('/product', ProductController::class)->except('show', 'update');
    Route::post('product/{product}/update', [ProductController::class, 'update'])->name('product.update');
    Route::post('product/{product}/upload-image', [ProductController::class, 'uploadImage'])->name('product.upload-image');
    Route::get('product/{productImage}/get-image', [ProductController::class, 'getImage'])->name('product.get-image');
    Route::post('product/{product}/delete-image', [ProductController::class, 'deleteImage'])->name('product.delete-image');

    Route::resource('/brand', BrandController::class)->except('create', 'show', 'edit', 'update');
    Route::post('brand/{brand}/update', [BrandController::class, 'update'])->name('brand.update');

    Route::resource('/banner', BannerController::class)->except('create', 'show', 'edit');
    Route::post('banner/{banner}/upload-image', [BannerController::class, 'uploadImage'])->name('banner.upload-image');

    Route::resource('/gallery', GalleryController::class)->except('create', 'show', 'edit');
    Route::post('gallery/{gallery}/upload-image', [GalleryController::class, 'uploadImage'])->name('gallery.upload-image');
    
    Route::get('image/{image}/', [ImageController::class, 'getImage'])->name('image.get-image');
    Route::post('delete-image', [ImageController::class, 'deleteImage'])->name('image.delete-image');

});