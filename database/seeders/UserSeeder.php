<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'username' => 'admin',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $user->user_id,
            'last_name' => 'Lavarias',
            'first_name' => 'Sebastian Curtis',
            'middle_name' => 'Tabucao',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);
    }
}
