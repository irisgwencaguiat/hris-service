<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInSpecialPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_special_payrolls', function (Blueprint $table) {
            $table->renameColumn(
                'special_payroll_type',
                'special_payroll_type_id'
            );

            $table
                ->foreign('special_payroll_type_id')
                ->references('special_payroll_type_id')
                ->on('ref_special_payroll_types');
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
