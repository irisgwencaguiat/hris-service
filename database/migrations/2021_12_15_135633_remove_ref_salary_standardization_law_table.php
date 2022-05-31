<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRefSalaryStandardizationLawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_salary_standardization_laws');

        Schema::table('ref_salary_grades', function (Blueprint $table) {
            $table->renameColumn('salary_standardization_law_id', 'salary_grade_version_id');
        });

        Schema::table('ref_step_increments', function (Blueprint $table) {
            $table->unsignedBigInteger('salary_grade_version_id')->after('step_increment_id')->comment('Salary Grade Version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
