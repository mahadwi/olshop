<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\API\BrandApiController;
use App\Http\Controllers\API\ColorApiController;
use App\Http\Controllers\API\EventApiController;
use App\Http\Controllers\API\BannerApiController;
use App\Http\Controllers\API\ReviewApiController;
use App\Http\Controllers\API\VendorApiController;
use App\Http\Controllers\API\AboutUsApiController;
use App\Http\Controllers\API\AddressApiController;
use App\Http\Controllers\API\BookingApiController;
use App\Http\Controllers\API\ContactApiController;
use App\Http\Controllers\API\CourierApiController;
use App\Http\Controllers\API\GalleryApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\RegisterApiController;
use App\Http\Controllers\API\WishlistApiController;
use App\Http\Controllers\API\KecamatanApiController;
use App\Http\Controllers\API\SubscribeApiController;
use App\Http\Controllers\API\CustomerCareApiController;
use App\Http\Controllers\API\ReturnPoliceApiController;
use App\Http\Controllers\API\PrivacyPoliceApiController;
use App\Http\Controllers\API\TermConditionApiController;
use App\Http\Controllers\API\EmailSubscribeApiController;
use App\Http\Controllers\API\ProductCategoryApiController;
use App\Http\Controllers\API\DeliveryShippingApiController;
use App\Http\Controllers\API\FaqApiController;
use App\Http\Controllers\API\SuggestionApiController;
use App\Http\Controllers\API\MessageApiController;
use App\Http\Controllers\API\VoucherApiController;
use App\Http\Controllers\API\AuthenticationApiController;
use App\Http\Controllers\API\OngkirApiController;
use App\Http\Controllers\XenditCallbackController;

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

Route::post('/register', [RegisterApiController::class, 'register']);
Route::post('verify-email', [RegisterApiController::class, 'verifyEmail'])->name('verify-email');
Route::post('request-verify-email', [RegisterApiController::class, 'requestVerifyEmail'])->name('request-verify-email');

Route::post('/login', [AuthController::class, 'login']);
// Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');
// Route::post('check-otp', [AuthController::class, 'checkOtp'])->name('check-otp');
// Route::post('request-otp', [AuthController::class, 'requestOtp'])->name('request-otp');

Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', [AuthController::class, 'user'])->name('user');
    Route::post('/user/update', [AuthController::class, 'updateUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('address', AddressApiController::class)->except('create', 'show', 'edit', 'index');

    Route::resource('wishlist', WishlistApiController::class)->except('create', 'show', 'edit');
    Route::resource('cart', CartApiController::class)->except('create', 'show', 'edit');

    Route::resource('vendor', VendorApiController::class)->except('create', 'show', 'edit', 'index');

    Route::resource('booking', BookingApiController::class)->only('index', 'show', 'store', 'delete');

});

Route::resource('product', ProductApiController::class)->only('index', 'show');
Route::resource('event', EventApiController::class)->only('index', 'show');

Route::get('brand', [BrandApiController::class, 'index']);
Route::get('color', [ColorApiController::class, 'index']);
Route::get('product-category', [ProductCategoryApiController::class, 'index']);
Route::get('banner', [BannerApiController::class, 'index']);
Route::get('gallery', [GalleryApiController::class, 'index']);

Route::get('about-us', [AboutUsApiController::class, 'index']);
Route::get('contact', [ContactApiController::class, 'index']);

Route::get('kecamatan', [KecamatanApiController::class, 'index']);

Route::get('return-police', [ReturnPoliceApiController::class, 'index']);
Route::get('privacy-police', [PrivacyPoliceApiController::class, 'index']);
Route::get('term-condition', [TermConditionApiController::class, 'index']);
Route::get('courier', [OngkirApiController::class, 'courier']);
Route::get('delivery-shipping', [DeliveryShippingApiController::class, 'index']);
Route::get('customer-care', [CustomerCareApiController::class, 'index']);
Route::get('subscribe', [SubscribeApiController::class, 'index']);
Route::get('review', [ReviewApiController::class, 'index']);
Route::post('/email-subscribe', [EmailSubscribeApiController::class, 'insert']);
Route::post('/message', [MessageApiController::class, 'insert']);

Route::resource('suggestion', SuggestionApiController::class)->only('store');
Route::get('faq', [FaqApiController::class, 'index']);
Route::get('authentication', [AuthenticationApiController::class, 'index']);

Route::resource('voucher', VoucherApiController::class)->only('index', 'show');

//callback invoice paid
Route::post('/callback', [XenditCallbackController::class, 'callback']);

//socialite auth
Route::get('auth', [AuthController::class, 'redirectToProvider']);
Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);

//cek ongkir
Route::post('ongkir', [OngkirApiController::class, 'ongkir']);


