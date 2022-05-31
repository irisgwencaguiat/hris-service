<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdittedEmployeeProfileCustomvaluesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_editted_employee_profile_customvalues', function (Blueprint $table) {
            $table->id('editted_employee_profile_customvalue_id');
            $table->unsignedBigInteger('editted_employee_profile_custom_id');

            $table->unsignedBigInteger('employee_profile_customvalue_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('employee_profile_customfield_id');
            $table->longText('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_editted_employee_profile_customvalues');
    }
}
