<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefPlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_plantillas', function (Blueprint $table) {
            $table->id('plantilla_id');
            $table->string('plantilla_no')->comment('Plantilla Item No.');
            $table->string('position_id')->comment('Position');
            $table->unsignedBigInteger('salary_grade_id')->nullable()->comment('Salary Grade');
            $table->unsignedBigInteger('step_increment_id')->nullable()->comment('Step Increment of Salary Grade');
            $table->double('salary', 14, 2)->default(0);
            $table->text('salary_in_word')->nullable();
            $table->unsignedBigInteger('office_id')->nullable()->comment('Office where plantilla is assigned');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['plantilla_no', 'office_id'], 'u_ref_plantillas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_plantillas');
    }
}
