<?php

namespace Database\Factories;

use App\Helpers\SubmissionStatusHelper;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Submission>
 */
class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::query()->inRandomOrder()->value('id');
        $promotion = Promotion::query()->inRandomOrder()->first();

        $selectedProducts = Product::query()
            ->inRandomOrder()
            ->limit(fake()->numberBetween(1, 4))
            ->get(['id', 'name', 'price']);

        $items = $selectedProducts
            ->map(fn (Product $product): array => [
                'id' => (int) $product->id,
                'name' => (string) $product->name,
                'price' => (int) $product->price,
            ])
            ->values()
            ->all();

        $purchaseDate = now()->subDays(fake()->numberBetween(1, 180));

        if ($promotion?->date_from && $promotion?->date_to) {
            $from = Carbon::parse($promotion->date_from)->startOfDay();
            $to = Carbon::parse($promotion->date_to)->endOfDay();

            if ($from->lte($to)) {
                $purchaseDate = fake()->dateTimeBetween($from, $to);
            }
        }

        return [
            'user_id' => $userId,
            'promotion_id' => $promotion?->id,
            'doc_img_path' => 'doc_images/' . fake()->uuid() . '.jpg',
            'ap_no' => fake()->numberBetween(100000, 999999999),
            'items' => $items,
            'status' => fake()->randomElement(array_keys(SubmissionStatusHelper::statuses())),
            'purchase_date' => Carbon::parse($purchaseDate)->toDateString(),
            'appeald_at' => null,
        ];
    }
}
