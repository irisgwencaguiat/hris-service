<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInEmployeeSpecialPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_special_payrolls', function (
            Blueprint $table
        ) {
            $table
                ->foreign('special_payroll_id')
                ->references('special_payroll_id')
                ->on('tbl_special_payrolls');

            $table
                ->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');
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
