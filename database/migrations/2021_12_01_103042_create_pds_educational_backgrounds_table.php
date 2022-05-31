<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsEducationalBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_educational_backgrounds', function (Blueprint $table) {
            $table->id('pds_educational_background_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('education_level');
            $table->string('school_name')->nullable();
            $table->string('degree')->nullable()->comment('Basic Education/Degree/Course');
            $table->string('attendance_from')->nullable()->comment('Period of Attendance From');
            $table->string('attendance_to')->nullable()->comment('Period of Attendance To');
            $table->string('units_earned')->nullable()->comment('Highest Level/Units Earned');
            $table->string('year_graduated')->nullable()->comment('Year Graduated');
            $table->string('honors_received')->nullable()->comment('Scholarship/Academic Honors Received');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_educational_backgrounds', [
            ['name' => 'degree', 'desc' => 'Basic Education/Degree/Course'],
            ['name' => 'attendance_from', 'desc' => 'Period of Attendance From'],
            ['name' => 'attendance_to', 'desc' => 'Period of Attendance To'],
            ['name' => 'units_earned', 'desc' => 'Highest Level/Units Earned'],
            ['name' => 'year_graduated', 'desc' => 'Year Graduated'],
            ['name' => 'honors_received', 'desc' => 'Scholarship/Academic Honors Received'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_educational_backgrounds');
    }
}
