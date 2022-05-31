<?php

namespace Database\Seeders;

use App\Models\RefCardType;
use Illuminate\Database\Seeder;

class RefCardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cardTypes = [
            ['card_type_name' => 'Cash Card'],
            ['card_type_name' => 'Payroll Savings'],
        ];
        foreach ($cardTypes as $cardType) {
            RefCardType::create($cardType);
        }
    }
}
