<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userOne = User::create([
            'username' => 'ryanazur',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userOne->user_id,
            'last_name' => 'Azur',
            'first_name' => 'Ryan',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userTwo = User::create([
            'username' => 'rizzamaeleonardo',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userTwo->user_id,
            'last_name' => 'Leonardo',
            'first_name' => 'Rizza Mae',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userThree = User::create([
            'username' => 'germelsevilla',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userThree->user_id,
            'last_name' => 'Sevilla',
            'first_name' => 'Germel',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userFour = User::create([
            'username' => 'joshuamamangun',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userFour->user_id,
            'last_name' => 'Mamangun',
            'first_name' => 'Joshua',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);

        $userFive = User::create([
            'username' => 'andrelaurio',
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        UserProfile::create([
            'user_id' => $userFive->user_id,
            'last_name' => 'Laurio',
            'first_name' => 'Andre',
            'middle_name' => '',
            'suffix' => '',
            'email' => '',
            'user_type_id' => 1,
            'user_classification_id' => 1,
        ]);
    }
}
