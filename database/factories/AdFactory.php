<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
        ];
    }
}
