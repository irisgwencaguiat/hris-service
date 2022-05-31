<?php

namespace Database\Seeders\References;

use Illuminate\Database\Seeder;
use App\Models\References\StepIncrement;

class StepIncrementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {
            StepIncrement::create([
                'step_increment_name' => $i
            ]);
        }
    }
}
