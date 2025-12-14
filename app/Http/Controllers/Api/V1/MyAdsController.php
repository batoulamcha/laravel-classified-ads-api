<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class MyAdsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $ads = Ad::where('user_id', $user->id)->paginate(10);

        return response()->json($ads);
    }
}
