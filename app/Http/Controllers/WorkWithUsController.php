<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\WorkWithUs;
use App\Models\WorkWithUsDetail;
use App\Models\WorkWithUsCard;
use Illuminate\Http\Request;
use App\Http\Requests\WorkWithUsStoreRequest;
use App\Http\Requests\WorkWithUsStoreSection1Request;
use App\Http\Requests\WorkWithUsStoreSection2Request;
use App\Http\Requests\WorkWithUsStoreSection3Request;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class WorkWithUsController extends Controller
{
    private function isImage(object $file)
    {
        return $file instanceof UploadedFile;
    }

    public function index(Request $request)
    {
        $workWithUs = WorkWithUs::get();
        $workWithUsDetail = WorkWithUsDetail::with('workWithUsCard')->get();

        return Inertia::render('WorkWithUs/Index', [
            'title' => 'Data '.__('app.label.work_with_us'),
            'workWithUs' => $workWithUs,
            'workWithUsDetail' => $workWithUsDetail,
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.work_with_us'), 'href' => route('work-with-us.index')],
            ],
        ]);
    }

    public function store(WorkWithUsStoreRequest $request)
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
            $workWithUs = WorkWithUs::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $params['title']]) . $th->getMessage());
        }
    }

    public function storeSection1(WorkWithUsStoreSection1Request $request)
    {
        $workWithUsDetail = WorkWithUsDetail::where('section', 1)->first();

        try {
           // Memeriksa apakah file gambar diunggah
        if ($request->hasFile('imageSection1')) {
            $file = $request->file('imageSection1');

           // Menghapus file lama jika ada
        if ($workWithUsDetail && File::exists(public_path('image/workWithUs/'.$workWithUsDetail->image))) {
            File::delete(public_path('image/workWithUs/'.$workWithUsDetail->image));
        }

            $uploadService = new UploadService();
            $uploadedFile = $uploadService->saveFile($file, 'workWithUs');

            // Menggunakan nama file yang diunggah untuk menyimpan ke database
            $params = [
                'work_with_us_id' => 1,
                'section' => 1,
                'title' => $request->titleSection1,
                'title_en' => $request->titleEnSection1,
                'description' => $request->descriptionSection1,
                'description_en' => $request->descriptionEnSection1,
                'image' => $uploadedFile['name'], // Menggunakan nama file yang diunggah
                'link' => $request->linkSection1,
            ];
        } else {
            // Jika tidak ada file gambar diunggah, gunakan nilai default atau kosong sesuai kebutuhan
            $params = [
                'work_with_us_id' => 1,
                'section' => 1,
                'title' => $request->titleSection1,
                'title_en' => $request->titleEnSection1,
                'description' => $request->descriptionSection1,
                'description_en' => $request->descriptionEnSection1,
                'image' => 'default.jpg', // Gunakan nilai default atau sesuai kebutuhan
                'link' => $request->linkSection1,
            ];
        }

            $condition = ['section' => 1];
            $workWithUs = WorkWithUsDetail::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $params['title']]) . $th->getMessage());
        }
    }

    public function storeSection2(WorkWithUsStoreSection2Request $request) {
        try {
            $workWithUsDetail = WorkWithUsDetail::updateOrCreate(
                ['section' => 2],
                [
                    'work_with_us_id' => 1,
                    'section' => 2,
                    'title' => $request->titleSection2,
                    'title_en' => $request->titleEnSection2,
                ]
            );

            $effectedCardIds = [];
            $cards = $request->cardsSection2;
            foreach ($cards as $card) {
                $cardModel = $card['id'] ? WorkWithUsCard::find($card['id']) : new WorkWithUsCard();

                $cardModel->fill([
                    'work_with_us_detail_id' => $workWithUsDetail->id,
                    'title' => $card['title'],
                    'title_en' => $card['title_en'],
                    'description' => $card['description'],
                    'description_en' => $card['description_en'],
                ]);

                if (isset($card['image']) && $this->isImage($card['image'])) {
                    $file = $card['image'];

                    if ($cardModel->icon) {
                        if(File::exists('image/workWithUs/'.$cardModel->icon)){
                            File::delete(public_path('image/workWithUs/'.$cardModel->icon));
                        }
                    }

                    $uploadedFile = (new UploadService())->saveFile($file, 'workWithUs');

                    $cardModel->fill([
                        'icon' => $uploadedFile['name'],
                    ]);
                }

                $cardModel->save();
                $effectedCardIds[] = $cardModel->id;
            }

            // Hapus card yang tidak terpakai
            WorkWithUsCard::where('work_with_us_detail_id', $workWithUsDetail->id)
                ->whereNotIn('id', $effectedCardIds)
                ->delete();

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->titleSection2]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->titleSection2]) . $th->getMessage());
        }

    }

    public function storeSection3(WorkWithUsStoreSection3Request $request)
    {
        // $request->dd();
        try {
            $workWithUsDetail = WorkWithUsDetail::updateOrCreate(
                ['section' => 3],
                [
                    'work_with_us_id' => 1,
                    'title' => $request->title,
                    'title_en' => $request->title_en,
                    'description' => $request->description,
                    'description_en' => $request->description_en,
                ]
            );

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if ($workWithUsDetail->image) {
                    if(File::exists('image/workWithUs/'.$workWithUsDetail->image)){
                        File::delete(public_path('image/workWithUs/'.$workWithUsDetail->image));
                    }
                }

                $uploadService = new UploadService();
                $uploadedFile = $uploadService->saveFile($file, 'workWithUs');

                $workWithUsDetail->update([
                    'image' => $uploadedFile['name'],
                ]);
            }

            return back()->with('success', __('app.label.created_successfully', ['name' => $request->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $request->title]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $workWithUs = WorkWithUs::find($id);
        if(!$workWithUs){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $workWithUs->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $workWithUs->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $workWithUs->title]) . $th->getMessage());
        }
    }
}
