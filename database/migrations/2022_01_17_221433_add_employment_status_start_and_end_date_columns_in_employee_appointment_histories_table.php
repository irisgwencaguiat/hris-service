<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmploymentStatusStartAndEndDateColumnsInEmployeeAppointmentHistoriesTable
    extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_appointment_histories', function (
            Blueprint $table
        ) {
            $table->date('employment_status_start_date')->nullable();
            $table->date('employment_status_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employee_appointment_histories', function (
            Blueprint $table
        ) {
            $table->dropColumn('employment_status_start_date');
            $table->dropColumn('employment_status_end_date');
        });
    }
}
