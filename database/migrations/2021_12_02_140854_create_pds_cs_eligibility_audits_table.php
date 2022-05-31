<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsCsEligibilityAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_cs_eligibility_audits', function (Blueprint $table) {
            $table->id('pds_cs_eligibility_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pcse_id');
            $table->unsignedBigInteger('employee_id');
            $table->text('service')->comment('Career Service/RA 1080 (Board Bar) under Special Laws/CES/CSEE/Barangay Eligibility/Driver License');
            $table->double('rating', 5, 2)->nullable()->comment('Rating (If applicable)');
            $table->date('date_of_exam')->nullable()->comment('Date of Examination/Conferment');
            $table->text('place_of_exam')->nullable()->comment('Place of Examination/Conferment');
            $table->string('license_no')->nullable()->comment('License Number (If applicable)');
            $table->string('date')->nullable()->comment('License Date of Validity (If applicable)');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_cs_eligibility_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
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
        Schema::dropIfExists('tbl_pds_cs_eligibility_audits');
    }
}
