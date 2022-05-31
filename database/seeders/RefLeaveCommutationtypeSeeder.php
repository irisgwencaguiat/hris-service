<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefLeaveCommutationtype;

class RefLeaveCommutationtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['leave_commutationtype_name' => 'Requested'],
            ['leave_commutationtype_name' => 'No Requested'],
        ];
        foreach ($types as $type) {
            RefLeaveCommutationtype::create($type);
        }
    }
}
