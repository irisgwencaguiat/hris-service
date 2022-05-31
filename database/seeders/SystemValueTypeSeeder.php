<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemValueType;

class SystemValueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['value_type_desc' => 'Normal Values'],
            ['value_type_desc' => 'Comma Separated Values'],
            ['value_type_desc' => 'JSON Formatted Values'],
        ];
        foreach ($types as $type) {
            SystemValueType::create($type);
        }
    }
}
