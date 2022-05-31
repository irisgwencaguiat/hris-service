<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefCitizenshipType;

class RefCitizenshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cTypes = [
            ['citizenship_type_id' => 'S', 'citizenship_type_name' => 'SINGLE CITIZENSHIP'],
            ['citizenship_type_id' => 'D', 'citizenship_type_name' => 'DUAL CITIZENSHIP'],
        ];
        foreach ($cTypes as $cType) {
            RefCitizenshipType::create($cType);
        }
    }
}
