<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\CategoryField;
use App\Models\AdFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreAdController extends Controller
{
    /**
     * Store a new ad with dynamic fields.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $categoryFields = CategoryField::where('category_id', $request->category_id)->get();

        $dynamicRules = [];
        foreach ($categoryFields as $field) {
            $rule = [];

            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            switch ($field->type) {
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'text':
                case 'textarea':
                    $rule[] = 'string';
                    break;
                case 'select':
                case 'radio':
                    if (!empty($field->options)) {
                        $rule[] = 'in:' . implode(',', $field->options->pluck('value')->toArray());
                    }
                    break;
            }

            $dynamicRules["fields.{$field->name}"] = $rule;
        }

        Validator::make($request->all(), $dynamicRules)->validate();

        $ad = Ad::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'user_id' => $request->user()->id,
        ]);

        foreach ($request->fields ?? [] as $name => $value) {
            $field = $categoryFields->firstWhere('name', $name);
            if ($field) {
                AdFieldValue::create([
                    'ad_id' => $ad->id,
                    'category_field_id' => $field->id,
                    'value' => $value,
                ]);
            }
        }

        return response()->json([
            'message' => 'Ad created successfully',
            'data' => $ad,
        ], 201);
    }

    /**
     * Update an existing ad (optional, static fields only)
     */
    public function update(Request $request, Ad $ad)
    {
        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $ad->update($request->only(['title','description','price','category_id']));

        return response()->json(['data' => $ad]);
    }

    /**
     * Delete an ad
     */
    public function destroy(Request $request, Ad $ad)
    {
        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $ad->delete();

        return response()->json(['message' => 'Ad deleted successfully.']);
    }
}
