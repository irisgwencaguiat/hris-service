<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_loans', function (Blueprint $table) {
            $table->id('employee_loan_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('loan_type_id');
            $table->double('amount', 14, 2)->default(0.00);
            $table->date('period_start');
            $table->date('period_end');
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');
                
            $table->foreign('loan_type_id')
                ->references('loan_type_id')
                ->on('ref_loan_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_loans');
    }
}
