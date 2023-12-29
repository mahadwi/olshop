<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FaqQuestionAnswer;
use App\Models\FaqSection;
use App\Models\FaqImage;
use Illuminate\Http\Request;
use App\Transformers\API\FaqTransformer;

class FaqApiController extends Controller
{
    public function index()
    {
        $data = FaqImage::first();
        $faq = fractal($data, new FaqTransformer)->parseIncludes(['faq_section'])->toArray();
        return $this->apiSuccess($faq);
    }
}
