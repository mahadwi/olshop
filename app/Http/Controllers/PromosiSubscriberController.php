<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PromoSubscribe;
use App\Models\Email;
use App\Models\Promo;
use Illuminate\Http\Request;
use App\Actions\StorePromoAction;
use App\Actions\UpdatePromoAction;
use App\Actions\StorePromoSubscribeAction;
use App\Actions\UpdateSubscribeAction;
use App\Http\Requests\PromoSubscribeStoreRequest;
use App\Http\Requests\PromoSubscribeUpdateRequest;
use App\Http\Requests\SubscribeUpdateRequest;
use App\Jobs\SendEmailPromotionSubscribesJobs;

class PromosiSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $promoSub = Promo::query();

        if ($request->has('search')) {
            $promoSub->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $promoSub->orderBy($request->field, $request->order);
        }

        $promoSub->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('PromosiSubscriber/Index', [
            'title'         => 'Data '.__('app.label.promo_subscribe'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'promoSubscribers' => $promoSub->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.promo_subscribe'), 'href' => route('promo-subscribe.index')],
            ],
        ]);
    }

    public function loadDetailEmail(Request $request) {
        $result = PromoSubscribe::select('emails.name', 'emails.email', 'promo_subscribes.status')
        ->join('emails', 'promo_subscribes.email_id', '=', 'emails.id')
        ->where('promo_subscribes.promo_id', $request->promo_id)
        ->get();
        return $result;
    }

    public function loadEmail(Request $request)
    {
        if ($request->promo_id) {
            $part1 = DB::table('emails as a')
            ->join('promo_subscribes as ps', 'a.id', '=', 'ps.email_id')
            ->where('ps.promo_id', '=', $request->promo_id)
            ->select('a.id','a.email', 'a.name', 'ps.status as status');

            // Second part of the union
            $part2 = DB::table('emails as a')
                ->whereNotIn('a.id', function ($query) use ($request) {
                    $query->select('emails.id')
                        ->from('promo_subscribes as ps2')
                        ->join('emails', 'emails.id', '=', 'ps2.email_id')
                        ->where('ps2.promo_id', '=', $request->promo_id);
                })
                ->select('a.id','a.email','a.name', DB::raw("'' as status"));

            // Combine both parts using union
            $results['data'] = $part1->union($part2)->get();
            return $results;
        }
        $promoSub = Email::with('promoSubscribes')->paginate(); // Adjust the number
        return $promoSub;
    }

    public function store(PromoSubscribeStoreRequest $request)
    {
        $promoSubscribe = new PromoSubscribe();
        $emails = new Email();
        // if($request->isCheckAll){
        //     $emails = Email::get();
        //     dd($emails);
        // }

        try {
            $promo = dispatch_sync(new StorePromoAction($request->all()));
            foreach ($request->isChecked as $key => $value) {
                $params = [
                    'promo_id' => $promo->id,
                    'email_id' => $value,
                    'status' => __('app.label.email_status') ,
                ];

                $promoSubscribe = dispatch_sync(new StorePromoSubscribeAction($params));
                 // Dispatch job untuk mengirim email
                 $getDataEmail = collect($emails->get())->where('id', $value)->first();
                 $checkEmail = Validator::make((array)$getDataEmail, [
                    'email' => 'email:rfc,dns',
                ]);
                if ($checkEmail->fails() === false) {
                   $dataParams = [
                        'to' => $getDataEmail->email,
                       'subject' => $request->post('title'),
                       'message' => $request->post('message')
                    ];
                    SendEmailPromotionSubscribesJobs::dispatch($dataParams);
                }else {

                }
            }
            return back()->with('success', __('app.label.created_successfully', ['name' => $promo ->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.list_email')]) . $th->getMessage());
        }
    }

    public function update(PromoSubscribeUpdateRequest $request, Promo $promo)
    {
        $promoSubscribe = new PromoSubscribe();
        $emails = new Email();
        try {
            $params = [
                'status' => __('app.label.email_status') ,
            ];
            $mergedData = array_merge($request->all(), $params);
            $promo = dispatch_sync(new UpdatePromoAction($promo, $mergedData));
            foreach ($request->isChecked as $key => $value) {
                $checkDataEmailId = collect($promoSubscribe->get())->where('promo_id', $request->promo_id)->where('email_id', $value);
                if(count($checkDataEmailId) <= 0) {
                    $params = [
                        'promo_id' => $promo->id,
                        'email_id' => $value,
                        'status' => __('app.label.email_status') ,
                    ];

                    $promoSubscribe = dispatch_sync(new StorePromoSubscribeAction($params));
                }
                $getDataEmail = collect($emails->get())->where('id', $value)->first();
                $checkEmail = Validator::make((array)$getDataEmail, [
                   'email' => 'email:rfc,dns',
               ]);
               if ($checkEmail->fails() === false) {
                  $dataParams = [
                      'to' => $getDataEmail->email,
                      'subject' => $request->post('title'),
                      'message' => $request->post('message')
                   ];
                   SendEmailPromotionSubscribesJobs::dispatch($dataParams);
               }
            }
            return back()->with('success', __('app.label.updated_successfully', ['name' => $promo->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $promo->title]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $promoSubscribe = Promo::find($id);
        if(!$promoSubscribe){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $promoSubscribe->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $promoSubscribe->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $promoSubscribe->title]) . $th->getMessage());
        }
    }
}
