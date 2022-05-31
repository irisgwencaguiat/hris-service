<?php

namespace Database\Seeders;

use App\Models\RefLearningDevelopmentType;
use Illuminate\Database\Seeder;

class FoundationForRefLearningDevelopmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefLearningDevelopmentType::create([
            'learndev_type_name' => 'Foundation',
        ]);
    }
}
