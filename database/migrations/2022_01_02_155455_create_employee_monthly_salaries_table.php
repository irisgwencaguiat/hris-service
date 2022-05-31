<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMonthlySalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_monthly_salaries', function (Blueprint $table) {
            $table->id('emp_monthly_salary_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedInteger('year');
            $table->unsignedInteger('month');
            $table->double('net_salary', 14, 2)->default(0.00);
            $table->double('net_salary_15th', 14, 2)->default(0.00);
            $table->double('net_salary_30th', 14, 2)->default(0.00);
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
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
        Schema::dropIfExists('tbl_employee_monthly_salaries');
    }
}
