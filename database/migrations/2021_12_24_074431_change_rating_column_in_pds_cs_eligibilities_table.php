<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRatingColumnInPdsCsEligibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_cs_eligibilities', function (Blueprint $table) {
            $table
                ->string('rating')
                ->nullable()
                ->comment('Rating (If applicable)')
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
        Schema::table('tbl_pds_cs_eligibilities', function (Blueprint $table) {
            $table
                ->double('rating', 5, 2)
                ->nullable()
                ->comment('Rating (If applicable)')
                ->change();
        });
    }
}
