<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSalaryGradeColumnsToPlantillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_plantillas', function (Blueprint $table) {
            $table->renameColumn('salary_grade_id', 'salary_grade_id');
            $table->renameColumn('step_increment_id', 'step_increment_id');
            $table->dropColumn('salary');
            $table->dropColumn('salary_in_word');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_plantillas', function (Blueprint $table) {
            //
        });
    }
}
