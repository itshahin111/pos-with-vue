<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Product::create([
                'name' => $faker->word,
                'price' => $faker->randomFloat(2, 10, 500), // Prices between 10 and 500
                'unit' => $faker->randomElement(['kg', 'pcs', 'box', 'liters']),
                'category_id' => rand(1, 5), // Assuming category IDs exist from 1 to 5
                'user_id' => 1
            ]);
        }
    }
}
