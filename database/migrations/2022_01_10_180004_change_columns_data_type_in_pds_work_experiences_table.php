<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsDataTypeInPdsWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_work_experiences', function (Blueprint $table) {
            $table
                ->string('date_from')
                ->nullable()
                ->comment('Inclusive Date From')
                ->change();
            $table
                ->string('date_to')
                ->nullable()
                ->comment('Inclusive Date To')
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
        Schema::table('tbl_pds_work_experiences', function (Blueprint $table) {
            $table
                ->date('date_from')
                ->nullable()
                ->comment('Inclusive Date From')
                ->change();
            $table
                ->date('date_to')
                ->nullable()
                ->comment('Inclusive Date To')
                ->change();
        });
    }
}
