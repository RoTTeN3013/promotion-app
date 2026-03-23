<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $targetCount = 100;
        $existingCount = Product::query()->count();
        $missingCount = max(0, $targetCount - $existingCount);

        if ($missingCount > 0) {
            Product::factory($missingCount)->create();
        }
    }
}
