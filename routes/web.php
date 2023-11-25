<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ContactController;
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
use App\Http\Controllers\ReturnPoliceController;
use App\Http\Controllers\PrivacyPoliceController;
use App\Http\Controllers\TermConditionController;
use App\Http\Controllers\DeliveryShippingController;

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

    Route::resource('/about', AboutUsController::class)->except('create', 'show', 'edit', 'update');
    Route::post('about/{about}/update', [AboutUsController::class, 'update'])->name('about.update');

    Route::resource('/contact', ContactController::class)->except('create', 'show', 'edit');

    Route::resource('/return-police', ReturnPoliceController::class)->except('create', 'show', 'edit', 'update');
    Route::post('return-police/{returnPolice}/update', [ReturnPoliceController::class, 'update'])->name('return-police.update');

    Route::resource('/privacy-police', PrivacyPoliceController::class)->except('create', 'show', 'edit', 'update');
    Route::post('privacy-police/{privacyPolice}/update', [PrivacyPoliceController::class, 'update'])->name('privacy-police.update');

    Route::resource('/term-condition', TermConditionController::class)->except('create', 'show', 'edit', 'update');
    Route::post('term-condition/{termCondition}/update', [TermConditionController::class, 'update'])->name('term-condition.update');

    Route::resource('/delivery-shipping', DeliveryShippingController::class)->except('create', 'show', 'edit', 'update');
    Route::post('delivery-shipping/{deliveryShipping}/update', [DeliveryShippingController::class, 'update'])->name('delivery-shipping.update');
});
