<?php

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use App\Helpers\AppHelper;
use App\Constants\OrderState;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Services\Ongkir\OngkirService;
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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GroupCoaController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\KonsinyasiController;
use App\Http\Controllers\PembelianAssetController;
use App\Http\Controllers\PenjualanAssetController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\PendaftaranAssetController;
use PDF;

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

    Route::resource('/order', OrderController::class)->only('index', 'show', 'update');
    Route::post('/cek-resi', [OrderController::class, 'cekResi']);
    Route::get('/order/{order}/print-label', [OrderController::class, 'printLabel'])->name('order.print-label');

    Route::get('cek-resi', function(){

        $orders = Order::where('status', OrderState::ONGOING)->get();
        $dataOrder = [];

        foreach($orders as $order){

            $now         = Carbon::now();
            $cekResi = (new OngkirService)->cekResi($order->courier, $order->resi);

            if($cekResi['status'] == 200){
                $statusOngkir = $cekResi['data']['summary']['status'];

                if($statusOngkir == 'DELIVERED'){            
                    // $dateDeliver = Carbon::parse($cekResi['data']['summary']['date']);        
                    // $diffDay = $dateDeliver->diffInDays($now);

                    // if($diffDay > 3){
                    //     $order->status = OrderState::COMPLETED;
                    //     $order->save();
                    // }

                    array_push($dataOrder, $order->code);

                }
            }
        };

        $stringOrder = implode(', ', $dataOrder);

        
    });

    Route::get('cek-order', function(){

        DB::transaction(function () {

            $orders = Order::with(['paymentable', 'orderDetail.product'])->where('status', OrderState::UNPAID)
            ->where('is_offline', false)
            ->whereHas('paymentable', function ($query){
                $query->where('expired', '<', Carbon::now());
            });

            $dataOrder = [];

            if($orders->get()->isNotEmpty()){

                $dataOrder = $orders->pluck('code')->toArray();       

                foreach($orders->get() as $order){
                    foreach($order->orderDetail as $detail){
                        $detail->product->stock += $detail->qty;
                        $detail->product->save();
                    }
                }

                $orders->update(['status' => OrderState::CANCEL]);

            }       

            $stringOrder = implode(', ', $dataOrder);

            \Log::info("Cek Order Berhasil di jalankan, set cancel order " . $stringOrder . ' ' . date('Y-m-d H:i:s'));
        
        });
    });

    Route::get('tes-helper', function(){
        // $date = AppHelper::dateIndo(date('Y-m-d'));

        $startDate = Carbon::parse('2023-04-20');
        $endDate = Carbon::parse('2024-03-15');

        // Hitung perbedaan bulan
        $monthDiff = $startDate->diffInMonths($endDate);

        dd($monthDiff);
        
    });

    Route::get('invoice', function(){   


        $pdf = PDF::loadview('mail.invoice');         
        
        return $pdf->stream('invoice.pdf', array("Attachment" => 0));
        // return view('mail.invoice');

    });
});

