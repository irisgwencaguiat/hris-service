<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInPdsEducationalBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_educational_backgrounds', function (Blueprint $table) {
            $table->date('attendance_from')->nullable()->comment('Period of Attendance From')->change();
            $table->date('attendance_to')->nullable()->comment('Period of Attendance To')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_pds_educational_backgrounds', function (Blueprint $table) {
            $table->string('attendance_from')->nullable()->comment('Period of Attendance From')->change();
            $table->string('attendance_to')->nullable()->comment('Period of Attendance To')->change();
        });
    }
}
