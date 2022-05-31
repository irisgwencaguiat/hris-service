<?php

namespace Database\Seeders;

use App\Models\RefPayrollType;
use Illuminate\Database\Seeder;

class RefPayrollTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payrollTypes = [
            ['payroll_type_name' => 'Permanent'],
            ['payroll_type_name' => 'Contractual'],
            ['payroll_type_name' => 'Cash Basis'],
        ];
        foreach ($payrollTypes as $payrollType) {
            RefPayrollType::create($payrollType);
        }
    }
}
