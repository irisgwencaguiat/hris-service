<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepIncrementIdToEmployeeAppointmentHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_appointment_histories', function (Blueprint $table) {
            // $table->unsignedBigInteger('step_increment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employee_appointment_histories', function (Blueprint $table) {
            // $table->dropColumn('step_increment_id');
        });
    }
}
