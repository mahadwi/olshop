<?php

namespace App\Actions\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\EventDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePaymentAction;
use App\Services\Xendit\XenditService;
use App\Services\Booking\BookingService;

class StoreBookingApiAction
{
    private $attributes;
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {
            
            //store booking
            $eventDetail = EventDetail::find($this->attributes['event_detail_id']);

            $booking = new Booking($this->attributes);

            $booking->price = $eventDetail->price;
            
            $booking->save();

            $external_id = (string) Str::uuid();

            $url = config('app.default.xendit_success_booking_url')."{$booking->eventDetail->event->id}/{$booking->eventDetail->id}?booking_id={$booking->id}";

            //create invoice
            $params = [
                'external_id'   => $external_id,
                'description'   => 'Ticket Payment',
                'amount'        => $this->attributes['total'],
                'success_redirect_url'  => $url
            ];
            
            $invoice = (new XenditService)->createInvoice($params);                            


            $paramPayment = [
                'external_id' => $external_id,
                'invoice_url' => $invoice['invoice_url'], 
                'expired' => Carbon::parse($invoice['expiry_date'])->setTimezone('Asia/Jakarta'),
            ];            


            //store payment
            dispatch_sync(new StorePaymentAction($paramPayment, $booking));

            return $booking;
        });
    }
}
