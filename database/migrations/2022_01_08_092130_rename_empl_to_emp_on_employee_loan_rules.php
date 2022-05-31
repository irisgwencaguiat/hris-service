<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEmplToEmpOnEmployeeLoanRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_loan_rules', function (Blueprint $table) {
            $table->renameColumn('empl_loan_rule_desc', 'emp_loan_rule_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employee_loan_rules', function (Blueprint $table) {
            //
        });
    }
}
