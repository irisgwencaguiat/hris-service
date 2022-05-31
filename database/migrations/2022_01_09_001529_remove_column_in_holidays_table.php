<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnInHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_holidays', function (Blueprint $table) {
            $table->dropColumn('year');
            $table
                ->text('description')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_holidays', function (Blueprint $table) {
            $table->string('year')->nullable();
            $table
                ->string('description')
                ->nullable()
                ->change();
        });
    }
}
