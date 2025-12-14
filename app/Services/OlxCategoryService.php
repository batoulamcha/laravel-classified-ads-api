<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OlxCategoryService
{
    protected $baseCategoriesUrl = 'https://www.olx.com.lb/api/categories/';
    protected $baseCategoryFieldsUrl = 'https://www.olx.com.lb/api/categoryFields';

    // Fetch categories with caching
    public function getCategories()
    {
        return Cache::remember('olx_categories', 86400, function () {
            $response = Http::get($this->baseCategoriesUrl);
            return $response->json()['data'] ?? [];
        });
    }

    // Fetch fields for a specific category with caching
    public function getCategoryFields(int $categoryExternalId)
    {
        $cacheKey = "olx_category_fields_{$categoryExternalId}";

        return Cache::remember($cacheKey, 86400, function () use ($categoryExternalId) {
            $response = Http::get($this->baseCategoryFieldsUrl, [
                'categoryExternalIDs' => $categoryExternalId,
                'includeWithoutCategory' => true,
                'splitByCategoryIDs' => true,
                'flatChoices' => true,
                'groupChoicesBySection' => true,
                'flat' => true,
            ]);

            return $response->json()['data'] ?? [];
        });
    }
}
