<?php

namespace Database\Seeders;

use App\Models\RefHolidayType;
use Illuminate\Database\Seeder;

class RefHolidayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidayTypes = [
            ['holiday_type_name' => 'Regular Holiday'],
            ['holiday_type_name' => 'Special (Non-Working) Holiday'],
            ['holiday_type_name' => 'Special Working Holiday'],
        ];
        foreach ($holidayTypes as $holidayType) {
            RefHolidayType::create($holidayType);
        }
    }
}
