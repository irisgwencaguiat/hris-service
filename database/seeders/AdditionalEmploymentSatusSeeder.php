<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefEmploymentStatus;

class AdditionalEmploymentSatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'RESIGNED',
            'TERMINATED',
            'RETIRED'
        ];

        foreach ($statuses as $status) {
            RefEmploymentStatus::create([
                'employment_status_name' => $status
            ]);
        }
    }
}
