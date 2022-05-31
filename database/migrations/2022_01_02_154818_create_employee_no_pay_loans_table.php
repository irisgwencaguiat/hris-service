<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeNoPayLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_no_pay_loans', function (Blueprint $table) {
            $table->id('emp_no_pay_loan_id');
            $table->unsignedBigInteger('employee_loan_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('period_start');
            $table->date('period_end');
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('employee_loan_id')
                ->references('employee_loan_id')
                ->on('tbl_employee_loans');

            $table->foreign('employee_id')
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
        Schema::dropIfExists('tbl_employee_no_pay_loans');
    }
}
