<?php

namespace Database\Seeders;

use App\Models\RefMonth;
use Illuminate\Database\Seeder;

class RefMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $months = [
            ['month_name' => 'January'],
            ['month_name' => 'February'],
            ['month_name' => 'March'],
            ['month_name' => 'April'],
            ['month_name' => 'May'],
            ['month_name' => 'June'],
            ['month_name' => 'July'],
            ['month_name' => 'August'],
            ['month_name' => 'September'],
            ['month_name' => 'October'],
            ['month_name' => 'November'],
            ['month_name' => 'December'],
        ];
        foreach ($months as $month) {
            RefMonth::create($month);
        }
    }
}
