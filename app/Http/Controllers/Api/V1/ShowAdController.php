<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class ShowAdController extends Controller
{
    public function show(Ad $ad)
    {
        return response()->json(['data' => $ad]);
    }
}
