<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPdsPersonalInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_personal_infos', function (Blueprint $table) {
            $table->string('bp_no')->nullable();
            $table->string('crn_umid_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_pds_personal_infos', function (Blueprint $table) {
            $table->dropColumn('bp_no');
            $table->dropColumn('crn_umid_no');
        });
    }
}
