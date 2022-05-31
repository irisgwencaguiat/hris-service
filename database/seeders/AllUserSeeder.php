<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            QAUserSeeder::class,
            DevUserSeeder::class,
            EncoderUserSeeder::class,
        ]);
    }
}
