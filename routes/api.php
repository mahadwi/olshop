<?php

use App\Http\Controllers\API\AboutUsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BannerApiController;
use App\Http\Controllers\API\BrandApiController;
use App\Http\Controllers\API\ColorApiController;
use App\Http\Controllers\API\ContactApiController;
use App\Http\Controllers\API\GalleryApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\ProductCategoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);    

});

Route::resource('product', ProductApiController::class)->only('index', 'show');

Route::get('brand', [BrandApiController::class, 'index']);
Route::get('color', [ColorApiController::class, 'index']);
Route::get('product-category', [ProductCategoryApiController::class, 'index']);
Route::get('banner', [BannerApiController::class, 'index']);
Route::get('gallery', [GalleryApiController::class, 'index']);

Route::get('about-us', [AboutUsApiController::class, 'index']);
Route::get('contact', [ContactApiController::class, 'index']);

