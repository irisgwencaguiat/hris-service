<?php

namespace Database\Seeders;
use App\Models\RefBloodType;

use Illuminate\Database\Seeder;

class RefBloodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bloodTypes = [
            ['blood_type_id' => 'O+', 'blood_type_name' => 'O+'],
            ['blood_type_id' => 'O-', 'blood_type_name' => 'O-'],
            ['blood_type_id' => 'A+', 'blood_type_name' => 'A+'],
            ['blood_type_id' => 'A-', 'blood_type_name' => 'A-'],
            ['blood_type_id' => 'B+', 'blood_type_name' => 'B+'],
            ['blood_type_id' => 'B-', 'blood_type_name' => 'B-'],
            ['blood_type_id' => 'AB+', 'blood_type_name' => 'AB+'],
            ['blood_type_id' => 'AB-', 'blood_type_name' => 'AB-'],
        ];
        foreach ($bloodTypes as $bloodType) {
            RefBloodType::create($bloodType);
        }
    }
}
