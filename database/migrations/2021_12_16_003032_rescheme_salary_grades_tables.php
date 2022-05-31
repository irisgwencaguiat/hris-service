<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReschemeSalaryGradesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_salary_grade_versions');
        Schema::dropIfExists('ref_salary_grades');
        Schema::dropIfExists('ref_step_increments');

        Schema::create('ref_salary_grades', function (Blueprint $table) {
            $table->id('salary_grade_id');
            $table->string('salary_grade_name')->unique();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('ref_step_increments', function (Blueprint $table) {
            $table->id('step_increment_id');
            $table->string('step_increment_name')->unique();

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
        //
    }
}
