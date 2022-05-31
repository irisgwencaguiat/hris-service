<?php

namespace Database\Seeders\References;

use Illuminate\Database\Seeder;
use App\Models\References\SalaryGrade;

class SalaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 33; $i++) {
            SalaryGrade::create([
                'salary_grade_name' => $i
            ]);
        }
    }
}
