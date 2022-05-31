<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSomeColumnsToNullableOnTrainLawAffecteds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_train_law_affecteds', function (Blueprint $table) {
            $table->unsignedBigInteger('employment_status_id')->nullable()->change();
            $table->unsignedBigInteger('position_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_train_law_affecteds', function (Blueprint $table) {
            //
        });
    }
}
