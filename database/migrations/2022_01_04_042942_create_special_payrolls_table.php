<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_special_payrolls', function (Blueprint $table) {
            $table->id('special_payroll_id');
            $table->string('special_payroll_no')->unique();
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('special_payroll_type');

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
        Schema::dropIfExists('tbl_special_payrolls');
    }
}
