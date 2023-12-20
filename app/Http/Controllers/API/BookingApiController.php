<?php

namespace App\Http\Controllers\API;

use Xendit\Xendit;
use Xendit\Invoice;
use App\Models\Booking;
use App\Models\Voucher;
use App\Models\EventDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreBookingApiAction;
use App\Http\Requests\API\BookingApiRequest;
use App\Transformers\API\BookingTransformer;

class BookingApiController extends Controller
{

    public function index()
    {
        $data = Booking::where('user_id', auth()->user()->id)->get();

        $booking = fractal($data, new BookingTransformer)->toArray();

        return $this->apiSuccess($booking);
    }

    public function store(BookingApiRequest $request)
    {
        $validate = $this->validateBooking($request);

        if($validate) return $this->apiError([], [], $validate);
        
        //store booking
        $booking = (new StoreBookingApiAction($request->all()))->handle();
        
        $booking = fractal($booking, new BookingTransformer)
            ->parseIncludes(['payment']);
            
        return $this->apiSuccess($booking);
    }

    public function validateBooking($request)
    {        
        $ticket = EventDetail::find($request->event_detail_id);

        $price = $ticket->price;
        $fixPrice = $price * $request->qty;

        // dd($fixPrice);
        
        if($request->voucher){
            $voucher = Voucher::where('code', $request->voucher)
            ->where('use_for', VoucherUseFor::EVENT)->first();

            if(!$voucher){
                return 'Voucher Use For Invalid';
            }

            //check type voucher
            if($voucher->type == VoucherType::B1G1){
                
                // if($request->qty < 2) return 'Qty minimal is 2';
                
                // $subtract = $price;

            } else if($voucher->type == VoucherType::PERCENT){
                $subtract = ($voucher->disc_percent / 100) * $fixPrice;
            } else if($voucher->type == VoucherType::PRICE){
                $subtract = $voucher->disc_price;
            }

            $fixPrice -= $subtract;
            
            
        }
        
        if($fixPrice != $request->total){
            return 'Total Invalid';
        }

        return false;
        
    
    }
}
