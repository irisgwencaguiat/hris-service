<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTblPlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tbl_plantillas');
        Schema::dropIfExists('tbl_plantilla_audits');
        Schema::dropIfExists('tbl_plantilla_items');
        Schema::dropIfExists('tbl_plantilla_item_audits');
        Schema::dropIfExists('tbl_plantilla_item_appointments');
        Schema::dropIfExists('tbl_plantilla_item_appointment_audits');
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
