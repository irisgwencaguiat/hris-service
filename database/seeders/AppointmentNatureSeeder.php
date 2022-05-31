<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefAppointmentNature;

class AppointmentNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $natures = [
            ['appointment_nature_name' => 'Original'],
            ['appointment_nature_name' => 'Promotion'],
            ['appointment_nature_name' => 'Reappointment'],
        ];
        foreach ($natures as $nature) {
            RefAppointmentNature::create($nature);
        }
    }
}
