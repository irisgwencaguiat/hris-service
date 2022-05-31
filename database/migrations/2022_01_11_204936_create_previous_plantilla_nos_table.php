<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviousPlantillaNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_previous_plantillas', function (Blueprint $table) {
            $table->id('previous_plantilla_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('plantilla_id');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table
                ->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');

            $table
                ->foreign('plantilla_id')
                ->references('plantilla_id')
                ->on('ref_plantillas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_previous_plantillas');
    }
}
