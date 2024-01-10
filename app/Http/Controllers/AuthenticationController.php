<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Authentication;
use App\Models\AuthenticationDetail;
use Illuminate\Http\Request;
use App\Http\Requests\AuthenticationStoreRequest;
use App\Http\Requests\AuthenticationStoreSection1Request;
use App\Http\Requests\AuthenticationStoreSection2Request;
use App\Http\Requests\AuthenticationStoreSection3Request;
use App\Http\Requests\AuthenticationUpdateRequest;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AuthenticationController extends Controller
{
    public function index(Request $request)
    {
        $authentication = Authentication::get();
        $authenticationDetail = AuthenticationDetail::get();

        return Inertia::render('Authentication/Index', [
            'title' => 'Data '.__('app.label.authentication'),
            'authentications'     => $authentication,
            'detailAuthentications' => $authenticationDetail,
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.authentication'), 'href' => route('authentication.index')],
            ],
        ]);
    }

    public function store(AuthenticationStoreRequest $request)
    {
        try {
            $params = [
                'id' => 1,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'description' => $request->description,
                'description_en' => $request->description_en,
                'no_hp' => $request->no_hp,
                'link' => $request->link,
            ];

            $condition = ['id' => 1];
            $authentication = Authentication::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => $params['title']]) . $th->getMessage());
        }
    }

    public function storeSection1(AuthenticationStoreSection1Request $request)
    {
        $authenticationDetail = AuthenticationDetail::where('section', 1)->first();

        try {
           // Memeriksa apakah file gambar diunggah
        if ($request->hasFile('imageSection1')) {
            $file = $request->file('imageSection1');

           // Menghapus file lama jika ada
        if ($authenticationDetail && File::exists(public_path('image/authentication/'.$authenticationDetail->image))) {
            File::delete(public_path('image/authentication/'.$authenticationDetail->image));
        }

            $uploadService = new UploadService();
            $uploadedFile = $uploadService->saveFile($file, 'authentication');

            // Menggunakan nama file yang diunggah untuk menyimpan ke database
            $params = [
                'authentication_id' => 1,
                'section' => 1,
                'title' => $request->titleSection1,
                'title_en' => $request->titleEnSection1,
                'description' => $request->descriptionSection1,
                'description_en' => $request->descriptionEnSection1,
                'image' => $uploadedFile['name'], // Menggunakan nama file yang diunggah
            ];
        } else {
            // Jika tidak ada file gambar diunggah, gunakan nilai default atau kosong sesuai kebutuhan
            $params = [
                'authentication_id' => 1,
                'section' => 1,
                'title' => $request->titleSection1,
                'title_en' => $request->titleEnSection1,
                'description' => $request->descriptionSection1,
                'description_en' => $request->descriptionEnSection1,
                'image' => '', // Gunakan nilai default atau sesuai kebutuhan
            ];
        }

            $condition = ['section' => 1];
            $authenticationDetail = AuthenticationDetail::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.authentication')]) . $th->getMessage());
        }
    }

    public function storeSection2(AuthenticationStoreSection2Request $request)
    {
        $authenticationDetail = AuthenticationDetail::where('section', 2)->first();

        try {
           // Memeriksa apakah file gambar diunggah
        if ($request->hasFile('imageSection2')) {
            $file = $request->file('imageSection2');

           // Menghapus file lama jika ada
        if ($authenticationDetail && File::exists(public_path('image/authentication/'.$authenticationDetail->image))) {
            File::delete(public_path('image/authentication/'.$authenticationDetail->image));
        }

            $uploadService = new UploadService();
            $uploadedFile = $uploadService->saveFile($file, 'authentication');

            // Menggunakan nama file yang diunggah untuk menyimpan ke database
            $params = [
                'authentication_id' => 1,
                'section' => 2,
                'title' => $request->titleSection2,
                'title_en' => $request->titleEnSection2,
                'description' => $request->descriptionSection2,
                'description_en' => $request->descriptionEnSection2,
                'image' => $uploadedFile['name'], // Menggunakan nama file yang diunggah
            ];
        } else {
            // Jika tidak ada file gambar diunggah, gunakan nilai default atau kosong sesuai kebutuhan
            $params = [
                'authentication_id' => 1,
                'section' => 2,
                'title' => $request->titleSection2,
                'title_en' => $request->titleEnSection2,
                'description' => $request->descriptionSection2,
                'description_en' => $request->descriptionEnSection2,
                'image' => '', // Gunakan nilai default atau sesuai kebutuhan
            ];
        }

            $condition = ['section' => 2];
            $authenticationDetail = AuthenticationDetail::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.authentication')]) . $th->getMessage());
        }
    }

    public function storeSection3(AuthenticationStoreSection3Request $request)
    {
        $authenticationDetail = AuthenticationDetail::where('section', 3)->first();

        try {
           // Memeriksa apakah file gambar diunggah
        if ($request->hasFile('imageSection3')) {
            $file = $request->file('imageSection3');

           // Menghapus file lama jika ada
        if ($authenticationDetail && File::exists(public_path('image/authentication/'.$authenticationDetail->image))) {
            File::delete(public_path('image/authentication/'.$authenticationDetail->image));
        }

            $uploadService = new UploadService();
            $uploadedFile = $uploadService->saveFile($file, 'authentication');

            // Menggunakan nama file yang diunggah untuk menyimpan ke database
            $params = [
                'authentication_id' => 1,
                'section' => 3,
                'title' => $request->titleSection3,
                'title_en' => $request->titleEnSection3,
                'description' => $request->descriptionSection3,
                'description_en' => $request->descriptionEnSection3,
                'image' => $uploadedFile['name'], // Menggunakan nama file yang diunggah
            ];
        } else {
            // Jika tidak ada file gambar diunggah, gunakan nilai default atau kosong sesuai kebutuhan
            $params = [
                'authentication_id' => 1,
                'section' => 3,
                'title' => $request->titleSection3,
                'title_en' => $request->titleEnSection3,
                'description' => $request->descriptionSection3,
                'description_en' => $request->descriptionEnSection3,
                'image' => '', // Gunakan nilai default atau sesuai kebutuhan
            ];
        }

            $condition = ['section' => 3];
            $authenticationDetail = AuthenticationDetail::updateOrInsert($condition, $params);

            return back()->with('success', __('app.label.created_successfully', ['name' => $params['title']]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.authentication')]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $authentication = Authentication::find($id);
        $authenticationDetailWhere = AuthenticationDetail::where('authentication_id', $id)->get();
        if(!$authentication){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            foreach ($authenticationDetailWhere as $k => $v) {
                $authenticationDetail = AuthenticationDetail::find($v->id);
                $authenticationDetail->delete();
            }
            $authentication->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $authentication->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $authentication->name]) . $th->getMessage());
        }
    }
}
