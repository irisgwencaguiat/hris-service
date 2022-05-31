<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_deductions', function (Blueprint $table) {
            $table->id('employee_deduction_id');
            $table->unsignedBigInteger('employee_loan_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedInteger('day')->comment('15 or 30');
            $table->date('deduction_date');
            $table->double('amount', 14, 2)->default(0.00);
            
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
        Schema::dropIfExists('tbl_employee_deductions');
    }
}
