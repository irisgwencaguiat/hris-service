<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefSalaryPer;

class RefSalaryPerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pers = [
            ['salary_per_name' => 'per year'],
            ['salary_per_name' => 'per month'],
            ['salary_per_name' => 'per hour'],
        ];
        foreach ($pers as $per) {
            RefSalaryPer::create($per);
        }
    }
}
