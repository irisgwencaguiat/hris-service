<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_working_hours', function (
            Blueprint $table
        ) {
            $table->id('employee_working_hour_id');
            $table->string('day');
            $table->string('day_slug');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->boolean('is_off')->default(false);
            $table->boolean('is_flexible')->default(false);
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_working_hours');
    }
}
