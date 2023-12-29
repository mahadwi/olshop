<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Consignment;
use App\Models\ConsignmentDetail;
use App\Models\ConsignmentCard;
use Illuminate\Http\Request;
use App\Actions\StoreReturnPoliceAction;
use App\Actions\UpdateReturnPoliceAction;
use App\Http\Requests\WorkWithUsStoreRequest;
use App\Http\Requests\WorkWithUsStoreSection1Request;
use App\Http\Requests\WorkWithUsStoreSection2Request;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class ConsignmentController extends Controller
{
    private function isImage(object $file)
    {
        return $file instanceof File;
    }

    public function index(Request $request)
    {
        $consignment = Consignment::get();
        $consignmentDetail = ConsignmentDetail::get();

        return Inertia::render('Consignment/Index', [
            'title' => 'Data '.__('app.label.consignment'),
            'consignment' => $consignment,
            'consignmentDetail' => $consignmentDetail,
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.consignment'), 'href' => route('consignment.index')],
            ],
        ]);
    }
}
