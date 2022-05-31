<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsCsEligibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_cs_eligibilities', function (Blueprint $table) {
            $table->id('pcse_id');
            $table->unsignedBigInteger('employee_id');
            $table->text('service')->comment('Career Service/RA 1080 (Board Bar) under Special Laws/CES/CSEE/Barangay Eligibility/Driver License');
            $table->double('rating', 5, 2)->nullable()->comment('Rating (If applicable)');
            $table->date('date_of_exam')->nullable()->comment('Date of Examination/Conferment');
            $table->text('place_of_exam')->nullable()->comment('Place of Examination/Conferment');
            $table->string('license_no')->nullable()->comment('License Number (If applicable)');
            $table->string('date')->nullable()->comment('License Date of Validity (If applicable)');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_cs_eligibilities', [
            ['name' => 'service', 'desc' => 'Career Service/RA 1080 (Board Bar) under Special Laws/CES/CSEE/Barangay Eligibility/Driver License'],
            ['name' => 'rating', 'desc' => 'Rating (If applicable)'],
            ['name' => 'date_of_exam', 'desc' => 'Date of Examination/Conferment'],
            ['name' => 'place_of_exam', 'desc' => 'Place of Examination/Conferment'],
            ['name' => 'license_no', 'desc' => 'License Number (If applicable)'],
            ['name' => 'date', 'desc' => 'License Date of Validity (If applicable)'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_cs_eligibilities');
    }
}
