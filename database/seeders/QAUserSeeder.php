<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class QAUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userOne = User::create([
            'username' => 'joshuarossmacabontoc',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userOne->user_id,
            'last_name' => 'Macabontoc',
            'first_name' => 'Joshua Ross',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userTwo = User::create([
            'username' => 'jemeirversoza',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userTwo->user_id,
            'last_name' => 'Versoza',
            'first_name' => 'Jemeir',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);
    }
}
