<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefCivilStatus;

class RefCivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $civilStatuses = [
            ['civil_status_name' => 'Single'],
            ['civil_status_name' => 'Married'],
            ['civil_status_name' => 'Widowed'],
            ['civil_status_name' => 'Separated'],
        ];
        foreach ($civilStatuses as $civilStatus) {
            RefCivilStatus::create($civilStatus);
        }
    }
}
