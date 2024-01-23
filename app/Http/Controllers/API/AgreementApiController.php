<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\VendorAgreement;
use App\Http\Controllers\Controller;
use App\Actions\UpdateVendorAgreementAction;
use App\Http\Requests\API\AgreementApiRequest;
use App\Http\Requests\UpdateAgreementApiRequest;
use App\Transformers\API\VendorAgreementTransformer;

class AgreementApiController extends Controller
{
    public function index(AgreementApiRequest $request)
    {
        $source = VendorAgreement::with('agreement')->where('vendor_product_id', $request->vendor_product_id)
        ->orderBy('id')->get();
        
        $data = fractal($source, new VendorAgreementTransformer())->toArray();

        return $this->apiSuccess($data);
    }

    public function update(UpdateAgreementApiRequest $request)
    {        
        $agreement = VendorAgreement::with('agreement')->find($request->id);

        $source = dispatch_sync(new UpdateVendorAgreementAction($agreement, $request->only('file')));

        $data = fractal($source, new VendorAgreementTransformer())->toArray();

        return $this->apiSuccess($data);
        
    }
}
