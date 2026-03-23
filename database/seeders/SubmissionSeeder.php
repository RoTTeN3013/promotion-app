<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionItem;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Promotion::query()->exists()) {
            $creatorId = User::query()->value('id');

            $minimumProductsForAutoPromotions = 25;
            $existingProducts = Product::query()->count();
            $missingProducts = max(0, $minimumProductsForAutoPromotions - $existingProducts);

            if ($missingProducts > 0) {
                Product::factory($missingProducts)->create();
            }

            $allProductIds = Product::query()->pluck('id')->all();

            for ($index = 1; $index <= 10; $index++) {
                $promotion = Promotion::query()->create([
                    'name' => 'Teszt promóció ' . $index,
                    'date_from' => now()->subDays(30),
                    'date_to' => now()->addDays(30),
                    'upload_from' => now()->subDays(15),
                    'upload_to' => now()->addDays(45),
                    'created_by' => $creatorId,
                ]);

                if (!empty($allProductIds)) {
                    $productIdsForPromotion = collect($allProductIds)
                        ->shuffle()
                        ->take(min(8, max(3, count($allProductIds))))
                        ->values();

                    foreach ($productIdsForPromotion as $productId) {
                        PromotionItem::query()->create([
                            'promotion_id' => $promotion->id,
                            'product_id' => $productId,
                        ]);
                    }
                }
            }
        }

        $targetCount = 400;
        $existingCount = Submission::query()->count();
        $missingCount = max(0, $targetCount - $existingCount);

        if ($missingCount > 0) {
            Submission::factory($missingCount)->create();
        }
    }
}
