<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficeCodeAndUniqueKeyToOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_offices', function (Blueprint $table) {
            $table->string('office_code', 50)->nullable()->after('office_id');

            $table->unique('office_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_offices', function (Blueprint $table) {
            //
        });
    }
}
