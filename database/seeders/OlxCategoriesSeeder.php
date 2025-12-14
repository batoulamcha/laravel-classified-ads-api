<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\CategoryFieldOption;
use Illuminate\Support\Str;

class OlxCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = Http::get('https://www.olx.com.lb/api/categories/')->json();

        foreach ($categories as $cat) {
            $category = Category::updateOrCreate(
                ['external_id' => $cat['id']],
                ['name' => $cat['name'], 'slug' => Str::slug($cat['name'])]
            );

            $fields = Http::get(
                "https://www.olx.com.lb/api/categoryFields?categoryExternalIDs={$cat['id']}&includeWithoutCategory=true&splitByCategoryIDs=true&flatChoices=true&groupChoicesBySection=true&flat=true"
            )->json();

            foreach ($fields as $field) {
                $catField = CategoryField::updateOrCreate(
                    ['category_id' => $category->id, 'name' => $field['name']],
                    ['label' => $field['label'], 'type' => $field['type'], 'required' => $field['required']]
                );

                if (!empty($field['choices'])) {
                    foreach ($field['choices'] as $choice) {
                        CategoryFieldOption::updateOrCreate(
                            ['category_field_id' => $catField->id, 'value' => $choice['value']],
                            ['label' => $choice['label'] ?? $choice['value']]
                        );
                    }
                }
            }
        }
    }
}
