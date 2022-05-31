<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeEvalformActualAccomplishmentIdNullableAtEvalFormComponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_evalform_components', function (Blueprint $table) {
            $table->unsignedBigInteger('evalform_actual_accomplishment_id')->nullable()->change();
        });
        Schema::table('tbl_evalform_component_audits', function (Blueprint $table) {
            $table->unsignedBigInteger('evalform_actual_accomplishment_id')->nullable()->change();
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
