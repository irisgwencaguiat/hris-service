<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInKiosksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_kiosks', function (Blueprint $table) {
            $table->renameColumn('device', 'passcode');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_kiosks', function (Blueprint $table) {
            $table->renameColumn('passcode', 'device');
            $table->dropColumn('description');
        });
    }
}
