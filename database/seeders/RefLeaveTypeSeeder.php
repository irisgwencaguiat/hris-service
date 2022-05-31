<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefLeaveType;

class RefLeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['leave_type_name' => 'Vacation Leave'],
            ['leave_type_name' => 'Sick Leave'],
            ['leave_type_name' => 'Maternity Leave'],
        ];
        foreach ($types as $type) {
            RefLeaveType::create($type);
        }
    }
}
