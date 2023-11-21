<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\KecamatanApiRequest;
use App\Models\Subdistrict;
use App\Transformers\API\SubdistrictTransformer;
use Illuminate\Http\Request;

class KecamatanApiController extends Controller
{
    public function index(KecamatanApiRequest $request)
    {
        $query = Subdistrict::where('name', 'ilike', "%{$request->name}%")->get();

        $kecamatan = fractal($query, new SubdistrictTransformer())->toArray();

        return $this->apiSuccess($kecamatan);
    }
}
