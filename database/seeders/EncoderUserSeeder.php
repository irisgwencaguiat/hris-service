<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class EncoderUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userOne = User::create([
            'username' => 'christineestrelon',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userOne->user_id,
            'last_name' => 'Estrelon',
            'first_name' => 'Christine',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userTwo = User::create([
            'username' => 'gianellitorres',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userTwo->user_id,
            'last_name' => 'Torres',
            'first_name' => 'Gianelli',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userThree = User::create([
            'username' => 'melissajalober',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userThree->user_id,
            'last_name' => 'jalober',
            'first_name' => 'melissa',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userFour = User::create([
            'username' => 'roelraen',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userFour->user_id,
            'last_name' => 'Raen',
            'first_name' => 'Roel',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);
    }
}
