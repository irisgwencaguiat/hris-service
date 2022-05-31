<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblActualSalaryGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_actual_salary_grades', function (Blueprint $table) {
            $table->id('actual_salary_grade_id');
            $table->unsignedBigInteger('salary_grade_version_id')->comment('Salary Grade Version');
            $table->unsignedBigInteger('salary_grade_id')->comment('Salary Grade');
            $table->double('step_1', 14, 2)->default(0);
            $table->double('step_2', 14, 2)->default(0);
            $table->double('step_3', 14, 2)->default(0);
            $table->double('step_4', 14, 2)->default(0);
            $table->double('step_5', 14, 2)->default(0);
            $table->double('step_6', 14, 2)->default(0);
            $table->double('step_7', 14, 2)->default(0);
            $table->double('step_8', 14, 2)->default(0);
            
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
        Schema::dropIfExists('tbl_actual_salary_grades');
    }
}
