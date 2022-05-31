<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefSex;

class RefSexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sexes = [
            ['sex_id' => 'M', 'sex_name' => 'Male'],
            ['sex_id' => 'F', 'sex_name' => 'Female'],
            ['sex_id' => 'U', 'sex_name' => 'Unknown'],
        ];
        foreach ($sexes as $sex) {
            RefSex::create($sex);
        }
    }
}
