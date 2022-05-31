<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefEmploymentStatus;

class RefEmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefEmploymentStatus::updateOrCreate(
            ['employment_status_id' => 1],
            ['employment_status_name' => 'REGULAR/PERMANENT']
        );
        RefEmploymentStatus::updateOrCreate(
            ['employment_status_id' => 2],
            ['employment_status_name' => 'PROBATIONARY']
        );
        RefEmploymentStatus::updateOrCreate(
            ['employment_status_id' => 3],
            ['employment_status_name' => 'CONTRACTUAL']
        );
        RefEmploymentStatus::updateOrCreate(
            ['employment_status_id' => 4],
            ['employment_status_name' => 'JOB ORDER']
        );
    }
}
