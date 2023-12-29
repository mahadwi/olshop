<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ReturnPoliceController;
use App\Http\Controllers\PrivacyPoliceController;
use App\Http\Controllers\TermConditionController;
use App\Http\Controllers\DeliveryShippingController;
use App\Http\Controllers\CustomerCareController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AuthenticationDetailController;
use App\Http\Controllers\WorkWithUsController;
use App\Http\Controllers\ConsignmentController;



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

Route::group(['middleware' => 'auth'], function () {

    Route::resource('/faq', FaqController::class)->except('show', 'update');
    Route::post('faq/{faq}/update', [FaqController::class, 'update'])->name('faq.update');
    Route::get('/get-faq-section', [FaqController::class, 'getFaqSection']);
    Route::post('faq-image', [FaqController::class, 'faqImage'])->name('faq.image');

    Route::resource('/voucher', VoucherController::class)->except('show');

    Route::resource('/about', AboutUsController::class)->except('create', 'show', 'edit', 'update');
    Route::post('about/{about}/update', [AboutUsController::class, 'update'])->name('about.update');

    Route::resource('/return-police', ReturnPoliceController::class)->except('create', 'show', 'edit', 'update');
    Route::post('return-police/{returnPolice}/update', [ReturnPoliceController::class, 'update'])->name('return-police.update');

    Route::resource('/privacy-police', PrivacyPoliceController::class)->except('create', 'show', 'edit', 'update');
    Route::post('privacy-police/{privacyPolice}/update', [PrivacyPoliceController::class, 'update'])->name('privacy-police.update');

    Route::resource('/term-condition', TermConditionController::class)->except('create', 'show', 'edit', 'update');
    Route::post('term-condition/{termCondition}/update', [TermConditionController::class, 'update'])->name('term-condition.update');

    Route::resource('/delivery-shipping', DeliveryShippingController::class)->except('create', 'show', 'edit', 'update');
    Route::post('delivery-shipping/{deliveryShipping}/update', [DeliveryShippingController::class, 'update'])->name('delivery-shipping.update');

    Route::resource('/customer-care', CustomerCareController::class)->except('create', 'show', 'edit', 'update');
    Route::post('customer-care/{customerCare}/update', [CustomerCareController::class, 'update'])->name('customer-care.update');

    Route::resource('/authentication', AuthenticationController::class)->except('create', 'show', 'edit', 'update');
    Route::get('/authentication/get-detail', [AuthenticationController::class, 'getDetail']);
    Route::post('authentication/{authentication}/update', [AuthenticationController::class, 'update'])->name('authentication.update');
    Route::resource('/authentication-detail', AuthenticationDetailController::class)->except('index','create', 'show', 'edit');

    Route::resource('/work-with-us', WorkWithUsController::class)->except('create', 'show', 'edit', 'update');
    Route::post('store-section1', [WorkWithUsController::class, 'storeSection1'])->name('work-with-us.storeSection1');
    Route::post('store-section2', [WorkWithUsController::class, 'storeSection2'])->name('work-with-us.storeSection2');
    Route::post('store-section3', [WorkWithUsController::class, 'storeSection3'])->name('work-with-us.storeSection3');

    Route::resource('/consignment', ConsignmentController::class)->except('create', 'show', 'edit', 'update');
    Route::post('store-consignment-section1', [ConsignmentController::class, 'storeSection1'])->name('consignment.storeSection1');
    Route::post('store-consignment-section2', [ConsignmentController::class, 'storeSection2'])->name('consignment.storeSection2');
    Route::post('store-consignment-section3', [ConsignmentController::class, 'storeSection3'])->name('consignment.storeSection3');
});
