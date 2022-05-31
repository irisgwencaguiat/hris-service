<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefLearningDevelopmentType;

class RefLearningDevelopmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['learndev_type_name' => 'Managerial'],
            ['learndev_type_name' => 'Supervisory'],
            ['learndev_type_name' => 'Technical'],
            ['learndev_type_name' => 'Foundation'],
        ];
        foreach ($types as $type) {
            RefLearningDevelopmentType::create($type);
        }
    }
}
