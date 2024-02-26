<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GroupCoaController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\KonsinyasiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembelianAssetController;
use App\Http\Controllers\PenjualanAssetController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\PendaftaranAssetController;

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

    Route::resource('/contact', ContactController::class)->except('create', 'show', 'edit');

    Route::resource('/pos', PosController::class)->only('index', 'show');

    Route::post('send-email', [SendEmailController::class, 'send'])->name('send-email');

    Route::resource('/pembelian-asset', PembelianAssetController::class);
    Route::resource('/pendaftaran-asset', PendaftaranAssetController::class);
    Route::resource('/penjualan-asset', PenjualanAssetController::class);
    Route::resource('/customer', CustomerController::class);

    Route::resource('/konsinyasi', KonsinyasiController::class);
    Route::post('konsinyasi/update-agreement', [KonsinyasiController::class, 'updateAgreement'])->name('konsinyasi.update-agreement');
    Route::post('konsinyasi/complete', [KonsinyasiController::class, 'complete'])->name('konsinyasi.complete');

    Route::resource('/order', OrderController::class)->only('index', 'show');


});

