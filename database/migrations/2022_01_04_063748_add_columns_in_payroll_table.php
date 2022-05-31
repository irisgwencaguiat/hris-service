<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payrolls', function (Blueprint $table) {
            $table->renameColumn('payroll_type', 'payroll_type_id');

            $table
                ->foreign('payroll_type_id')
                ->references('payroll_type_id')
                ->on('ref_payroll_types');

            $table
                ->foreign('department_id')
                ->references('department_id')
                ->on('ref_departments');
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
