<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoanDeductionColumnsToEmployeeMonthlySalaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_monthly_salaries', function (Blueprint $table) {
            $table->double('loan_deduction', 14, 2)->default(0.00)->after('net_salary_30th');
            $table->double('loan_deduction_15th', 14, 2)->default(0.00)->after('loan_deduction');
            $table->double('loan_deduction_30th', 14, 2)->default(0.00)->after('loan_deduction_15th');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employee_monthly_salaries', function (Blueprint $table) {
            //
        });
    }
}
