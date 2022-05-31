<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefCitizenshipProcess;

class RefCitizenshipProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cProcesses = [
            ['citizenship_process_id' => 'B', 'citizenship_process_name' => 'By Birth'],
            ['citizenship_process_id' => 'N', 'citizenship_process_name' => 'By Naturalization'],
        ];
        foreach ($cProcesses as $cProcess) {
            RefCitizenshipProcess::create($cProcess);
        }
    }
}
