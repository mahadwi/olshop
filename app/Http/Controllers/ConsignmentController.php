<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Consignment;
use Illuminate\Http\Request;
use App\Models\ConsignmentCard;
use App\Models\ConsignmentDetail;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Actions\StoreReturnPoliceAction;
use App\Actions\UpdateReturnPoliceAction;
use App\Constants\LoadMoreType;
use App\Http\Requests\WorkWithUsStoreRequest;
use App\Http\Requests\ConsignmentStoreRequest;
use App\Http\Requests\WorkWithUsStoreSection1Request;
use App\Http\Requests\WorkWithUsStoreSection2Request;
use App\Http\Requests\ConsignmentStoreSection1Request;
use App\Http\Requests\ConsignmentStoreSection2Request;
use App\Http\Requests\ConsignmentStoreSection4Request;
use App\Http\Requests\ConsignmentStoreSection5Request;
use App\Http\Requests\ConsignmentStoreSection6Request;

class ConsignmentController extends Controller
{
    private function isImage(object $file)
    {
        return $file instanceof File;
    }

    public function index(Request $request)
    {
        $consignment = Consignment::get();
        $consignmentDetail = ConsignmentDetail::with('consignmentCard')->get();
        $loadmoreType = collect(LoadMoreType::getValues());

        return Inertia::render('Consignment/Index', [
            'title' => 'Data '.__('app.label.consignment'),
            'consignment' => $consignment,
            'loadmoreType' => $loadmoreType,
            'consignmentDetail' => $consignmentDetail,
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.consignment'), 'href' => route('consignment.index')],
            ],
        ]);
    }

    public function store(ConsignmentStoreRequest $request)
    {
        try {
            $params = [
                'id' => 1,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'description' => $request->description,
                'description_en' => $request->description_en,
            ];

            $condition = ['id' => 1];
            $consignment = Consignment::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $params['title']]) . $th->getMessage());
        }
    }

    public function storeSection1(ConsignmentStoreSection1Request $request)
    {
        try {
           // Memeriksa apakah file gambar diunggah
       
            $params = [
                'consignment_id' => 1,
                'section' => 1,
                'title' => $request->titleSection1,
                'title_en' => $request->titleEnSection1,
                'description' => $request->descriptionSection1,
                'description_en' => $request->descriptionEnSection1,
            ];

            $condition = ['section' => 1];
            $update = ConsignmentDetail::updateOrInsert($condition, $params);

            $consignmentDetail = ConsignmentDetail::where('section', 1)->first();
            
            if ($request->hasFile('imageSection1')) {
                $file = $request->file('imageSection1');

                if ($consignmentDetail->image) {
                    if(File::exists('image/consignment/'.$consignmentDetail->image)){
                        File::delete(public_path('image/consignment/'.$consignmentDetail->image));
                    }
                }

                $uploadService = new UploadService();
                $uploadedFile = $uploadService->saveFile($file, 'consignment');

                $consignmentDetail->update([
                    'image' => $uploadedFile['name'],
                ]);
            }

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $params['title']]) . $th->getMessage());
        }
    }

    public function storeSection2(ConsignmentStoreSection2Request $request)
    {
        try {
            $consignmentDetail = ConsignmentDetail::updateOrCreate(
                ['section' => 2],
                [
                    'consignment_id' => 1,
                    'title' => $request->titleSection2,
                    'title_en' => $request->titleEnSection2,
                    'description' => $request->descriptionSection2,
                    'description_en' => $request->descriptionEnSection2,
                    'link' => $request->linkSection2,
                ]
            );            

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->title]) . $th->getMessage());
        }
    }

    public function storeSection4(ConsignmentStoreSection4Request $request) {
        try {
            $consignmentDetail = ConsignmentDetail::updateOrCreate(
                ['section' => 4],
                [
                    'consignment_id' => 1,
                    'section' => 4,
                    'title' => $request->titleSection4,
                    'title_en' => $request->titleEnSection4,
                ]
            );

            if ($request->hasFile('imageSection4')) {
                $file = $request->file('imageSection4');

                if ($consignmentDetail->image) {
                    if(File::exists('image/consignment/'.$consignmentDetail->image)){
                        File::delete(public_path('image/consignment/'.$consignmentDetail->image));
                    }
                }

                $uploadService = new UploadService();
                $uploadedFile = $uploadService->saveFile($file, 'consignment');

                $consignmentDetail->update([
                    'image' => $uploadedFile['name'],
                ]);
            }


            $effectedCardIds = [];
            $cards = $request->cardsSection4;
            foreach ($cards as $card) {
                $cardModel = $card['id'] ? ConsignmentCard::find($card['id']) : new ConsignmentCard();

                $cardModel->fill([
                    'consignment_detail_id' => $consignmentDetail->id,
                    'title' => $card['title'],
                    'title_en' => $card['title_en'],
                    'description' => $card['description'],
                    'description_en' => $card['description_en'],
                    'loadmore_type' => $card['loadmore_type'],
                    'loadmore_link' => $card['loadmore_link'],
                    'loadmore_text' => $card['loadmore_text'],
                ]);
                
                if (isset($card['image']) ) {
                    $file = $card['image'];

                    if ($cardModel->icon) {
                        if(File::exists('image/consignment/'.$cardModel->icon)){
                            File::delete(public_path('image/consignment/'.$cardModel->icon));
                        }
                    }

                    $uploadedFile = (new UploadService())->saveFile($file, 'consignment');

                    $cardModel->fill([
                        'icon' => $uploadedFile['name'],
                    ]);
                }

                $cardModel->save();
                $effectedCardIds[] = $cardModel->id;
            }

            // Hapus card yang tidak terpakai
            ConsignmentCard::where('consignment_detail_id', $consignmentDetail->id)
                ->whereNotIn('id', $effectedCardIds)
                ->delete();

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->titleSection2]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->titleSection2]) . $th->getMessage());
        }

    }

    public function storeSection5(ConsignmentStoreSection5Request $request) {
        try {
            $consignmentDetail = ConsignmentDetail::updateOrCreate(
                ['section' => 5],
                [
                    'consignment_id' => 1,
                    'section' => 5,
                    'title' => $request->titleSection5,
                    'title_en' => $request->titleEnSection5,
                ]
            );            

            if ($request->hasFile('imageSection5')) {
                $file = $request->file('imageSection5');

                if ($consignmentDetail->image) {
                    if(File::exists('image/consignment/'.$consignmentDetail->image)){
                        File::delete(public_path('image/consignment/'.$consignmentDetail->image));
                    }
                }

                $uploadService = new UploadService();
                $uploadedFile = $uploadService->saveFile($file, 'consignment');

                $consignmentDetail->update([
                    'image' => $uploadedFile['name'],
                ]);
            }


            $effectedCardIds = [];
            $cards = $request->cardsSection5;
            foreach ($cards as $card) {
                $cardModel = $card['id'] ? ConsignmentCard::find($card['id']) : new ConsignmentCard();

                $cardModel->fill([
                    'consignment_detail_id' => $consignmentDetail->id,
                    'title'                 => $card['title'],
                    'title_en'              => $card['title_en'],
                    'description'           => $card['description'],
                    'description_en'        => $card['description_en'],
                    'loadmore_type'         => $card['loadmore_type'],
                    'loadmore_link'         => $card['loadmore_link'],
                    'loadmore_text'         => $card['loadmore_text'],
                ]);
                
                if (isset($card['image']) ) {
                    $file = $card['image'];

                    if ($cardModel->icon) {
                        if(File::exists('image/consignment/'.$cardModel->icon)){
                            File::delete(public_path('image/consignment/'.$cardModel->icon));
                        }
                    }

                    $uploadedFile = (new UploadService())->saveFile($file, 'consignment');

                    $cardModel->fill([
                        'icon' => $uploadedFile['name'],
                    ]);
                }

                $cardModel->save();
                $effectedCardIds[] = $cardModel->id;
            }

            // Hapus card yang tidak terpakai
            ConsignmentCard::where('consignment_detail_id', $consignmentDetail->id)
                ->whereNotIn('id', $effectedCardIds)
                ->delete();

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->titleSection2]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->titleSection2]) . $th->getMessage());
        }

    }

    public function storeSection6(ConsignmentStoreSection6Request $request)
    {
        try {
            $consignmentDetail = ConsignmentDetail::updateOrCreate(
                ['section' => 6],
                [
                    'consignment_id' => 1,
                    'title' => $request->titleSection6,
                    'title_en' => $request->titleEnSection6,
                    'description' => $request->descriptionSection6,
                    'description_en' => $request->descriptionEnSection6,
                    'link' => $request->linkSection6,
                ]
            );            

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->title]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = Consignment::find($id);
        if(!$model){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $model->delete();

            //delete image folder
            $folder = public_path('image/consignment');
            if(File::exists($folder)){
                File::deleteDirectory($folder);
            }

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $model->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $model->title]) . $th->getMessage());
        }
    }
}
