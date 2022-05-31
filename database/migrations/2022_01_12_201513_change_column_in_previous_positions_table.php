<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnInPreviousPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_previous_positions', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['position_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_previous_positions', function (Blueprint $table) {
            $table
                ->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');

            $table
                ->foreign('position_id')
                ->references('position_id')
                ->on('ref_positions');
        });
    }
}
