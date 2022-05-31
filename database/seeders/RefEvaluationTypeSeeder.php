<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefEvaluationType;

class RefEvaluationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['eval_type_id' => 'IPCR', 'eval_type_name' => 'Individual Performance Commitment and Review'],
            ['eval_type_id' => 'OPCR', 'eval_type_name' => 'Individual Performance Commitment and Review'],
        ];
        foreach ($types as $type) {
            RefEvaluationType::create($type);
        }
    }
}
