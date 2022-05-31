<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefEducationLevel;

class RefEducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            ['education_level_name' => 'Elementary'],
            ['education_level_name' => 'Secondary'],
            ['education_level_name' => 'Vocational/Trade Course'],
            ['education_level_name' => 'College'],
            ['education_level_name' => 'Graduate Studies'],
        ];
        foreach ($levels as $level) {
            RefEducationLevel::create($level);
        }
    }
}
