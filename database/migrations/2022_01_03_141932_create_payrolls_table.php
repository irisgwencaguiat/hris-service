<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payrolls', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->string('payroll_no')->unique();
            $table->unsignedBigInteger('payroll_type');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('period_covered')->nullable();
            $table->string('pay_period')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_payrolls');
    }
}
