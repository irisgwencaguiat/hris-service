<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UpdateCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdditionalEmploymentSatusSeeder::class,
        ]);
    }
}
