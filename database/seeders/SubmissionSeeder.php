<?php

namespace Database\Seeders;

use App\Models\Promotion;
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

            for ($index = 1; $index <= 10; $index++) {
                Promotion::query()->create([
                    'name' => 'Teszt promóció ' . $index,
                    'date_from' => now()->subDays(30),
                    'date_to' => now()->addDays(30),
                    'upload_from' => now()->subDays(15),
                    'upload_to' => now()->addDays(45),
                    'created_by' => $creatorId,
                ]);
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
