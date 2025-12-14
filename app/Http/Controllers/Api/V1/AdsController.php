<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::query();

        if ($request->has('category_id')) {
            $ads->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $ads->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $ads->where('price', '<=', $request->max_price);
        }

        if ($request->has('q')) {
            $ads->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%');
        }
        
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $ads->orderBy($sortField, $sortOrder);

        $perPage = $request->get('per_page', 10);
        $ads = $ads->paginate($perPage);

        return response()->json($ads);
    }
}
