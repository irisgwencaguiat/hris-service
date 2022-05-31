<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefSalaryGrade;
use App\Models\RefStepIncrement;

class RefSalaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salaryGrades = [
            [
                'number' => 1,
                'name' => 'S1',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 2,
                'name' => 'S2',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 3,
                'name' => 'S3',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 4,
                'name' => 'S4',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 5,
                'name' => 'S5',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 6,
                'name' => 'S6',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 7,
                'name' => 'S7',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 8,
                'name' => 'S8',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 9,
                'name' => 'S9',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 10,
                'name' => 'S10',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 11,
                'name' => 'S11',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 12,
                'name' => 'S12',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 13,
                'name' => 'S13',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 14,
                'name' => 'S14',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
            [
                'number' => 15,
                'name' => 'S15',
                'step_increments' => [
                    11551,
                    11647,
                    11745,
                    11843,
                    11942,
                    12042,
                    12143,
                    12244
                ]
            ],
        ];

        foreach ($salaryGrades as $salaryGrade) {
            $sg = RefSalaryGrade::create([
                'number' => $salaryGrade['number'],
                'name' => $salaryGrade['name'],
                'salary_standardization_law_id' => 1,
            ]);
            
            for ($i = 0; $i < count($salaryGrade['step_increments']); $i++) {
                RefStepIncrement::create([
                    'salary_grade_id' => $sg->salary_grade_id,
                    'number' => $i+1,
                    'name' => 'I' . ($i+1),
                    'salary' => $salaryGrade['step_increments'][$i]
                ]);
            }
        }
    }
}
