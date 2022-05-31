<?php

namespace Database\Seeders;

use App\Models\RefDesignation;
use Illuminate\Database\Seeder;

class RefDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            ['designation_name' => 'Department Head'],
            ['designation_name' => 'Supervisory'],
            ['designation_name' => 'Office/Department Staff'],
        ];
        foreach ($designations as $designation) {
            RefDesignation::create($designation);
        }
    }
}
