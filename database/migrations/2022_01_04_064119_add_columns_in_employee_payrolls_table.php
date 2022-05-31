<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInEmployeePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_payrolls', function (Blueprint $table) {
            $table
                ->foreign('payroll_id')
                ->references('payroll_id')
                ->on('tbl_payrolls');

            $table
                ->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');

            $table
                ->foreign('position_id')
                ->references('position_id')
                ->on('ref_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
