<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PickupDuration;
use App\Http\Controllers\Controller;
use App\Transformers\API\PickupDurationTransformer;
use PHPUnit\Event\Telemetry\Duration;

class OperationalApiController extends Controller
{
    public function __invoke()
    {
        $duration = PickupDuration::first();

        $data = fractal($duration, new PickupDurationTransformer())->toArray();

        return $this->apiSuccess($data);
    }
}
