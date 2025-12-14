<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\OlxCategoryService;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\CategoryFieldOption;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $service = new OlxCategoryService();

        $categories = $service->getCategories();

        foreach ($categories as $cat) {
            // Save category
            $category = Category::updateOrCreate(
                ['external_id' => $cat['id']],
                ['name' => $cat['name'], 'slug' => \Str::slug($cat['name'])]
            );
            $this->command->info("Seeded category: {$category->name}");

            // Fetch fields for this category
            $fields = $service->getCategoryFields($cat['id']);

            foreach ($fields as $fieldData) {
                $field = CategoryField::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => \Str::slug($fieldData['name']),
                    ],
                    [
                        'name' => $fieldData['name'],
                        'type' => $fieldData['type'] ?? 'text',
                        'is_required' => $fieldData['required'] ?? false,
                    ]
                );

                // Save options for select/radio fields
                if (in_array($fieldData['type'] ?? '', ['select', 'radio']) && !empty($fieldData['choices'])) {
                    foreach ($fieldData['choices'] as $choice) {
                        CategoryFieldOption::updateOrCreate(
                            [
                                'category_field_id' => $field->id,
                                'value' => $choice['value'] ?? $choice['label'] ?? '',
                            ],
                            [
                                'label' => $choice['label'] ?? $choice['value'] ?? '',
                            ]
                        );
                    }
                }
            }
        }
    }
}
