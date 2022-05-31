<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefUserClassification;

class RefUserClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classifications = [
            ['user_classification_name' => 'Employee'],
            ['user_classification_name' => 'HR Admin'],
        ];
        foreach ($classifications as $classification) {
            RefUserClassification::create($classification);
        }
    }
}
