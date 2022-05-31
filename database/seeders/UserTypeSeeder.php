<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['user_type_name' => 'Administrator'],
            ['user_type_name' => 'User'],
        ];
        foreach ($types as $type) {
            UserType::create($type);
        }
    }
}
