<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employees', function (Blueprint $table) {
            $table->renameColumn('department', 'department_id');
            $table->unsignedBigInteger('office_id')->nullable()->comment('Office');
            $table->unsignedBigInteger('employment_status_id')->nullable()->comment('Employment Status');
            $table->unsignedBigInteger('appointment_nature_id')->nullable()->comment('Appointment Nature');
            $table->string('remarks')->nullable()->comment('Remarks for Nature of Appointment.');
            $table->unsignedBigInteger('salary_grade_id')->nullable()->comment('Salary Grade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employees', function (Blueprint $table) {
            $table->renameColumn('department_id', 'department');
            $table->dropColumn('office_id')->nullable()->comment('Office');
            $table->dropColumn('employment_status_id')->nullable()->comment('Employment Status');
            $table->dropColumn('appointment_nature_id')->nullable()->comment('Appointment Nature');
            $table->dropColumn('remarks')->nullable()->comment('Remarks for Nature of Appointment.');
            $table->dropColumn('salary_grade_id')->nullable()->comment('Salary Grade');
        });
    }
}
