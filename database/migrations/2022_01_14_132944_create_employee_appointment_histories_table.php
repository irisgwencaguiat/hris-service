<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAppointmentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_appointment_histories', function (
            Blueprint $table
        ) {
            $table->id('employee_appointment_history_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamp('end_date')->nullable();
            $table->unsignedBigInteger('plantilla_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('salary_grade_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('step_increment_id')->nullable();
            $table->unsignedBigInteger('employment_status_id')->nullable();
            $table->unsignedBigInteger('appointment_nature_id')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('tbl_employee_appointment_histories');
    }
}
