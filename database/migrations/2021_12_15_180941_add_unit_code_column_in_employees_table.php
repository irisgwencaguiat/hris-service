<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitCodeColumnInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->comment('Office');
            $table->unsignedBigInteger('step_increment_id')->nullable()->comment('Unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employees', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->dropColumn('step_increment_id');
        });
    }
}
