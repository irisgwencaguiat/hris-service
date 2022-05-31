<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnInPreviousPlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_previous_plantillas', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['plantilla_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_previous_plantillas', function (Blueprint $table) {
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
}
