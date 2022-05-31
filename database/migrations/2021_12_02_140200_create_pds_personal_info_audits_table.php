<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsPersonalInfoAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_personal_info_audits', function (Blueprint $table) {
            $table->id('pds_personal_info_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pds_personal_info_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('last_name', 50);
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('suffix', 20)->nullable()->comment('Extension name');
            $table->date('date_of_birth');
            $table->string('sex', 10);
            $table->string('civil_status', 10);
            $table->double('height', 6, 2)->nullable()->comment('Height in meters');
            $table->double('weight', 6, 2)->nullable()->comment('Weight in killograms');
            $table->string('blood_type', 10)->nullable();
            $table->string('gsis_no', 100)->nulalble();
            $table->string('pagibig_no', 100)->nulalble();
            $table->string('philhealth_no', 100)->nulalble();
            $table->string('sss_no', 100)->nulalble();
            $table->string('tin_no', 100)->nulalble();
            $table->string('agency_employee_no', 100)->nulalble()->comment('Agency Employee No.');

            $table->string('citizenship_type', 10)->nullable()->comment('Either Single or Dual Citizenship');
            $table->string('citizenship_process', 10)->nullable()->comment('Either by Birth or by Naturalization');
            $table->string('primary_country', 10)->nullable()->comment('Primary Citizenship Country');
            $table->string('secondary_country', 10)->nullable()->comment('For Dual Citizenship');
            
            $table->string('residential_address_line_1')->nullable()->comment('Residential House/Block/Lot No.');
            $table->string('residential_street')->nullable()->comment('Residential Street');
            $table->string('residential_village')->nullable()->comment('Residential Subdivision/Vilalge');
            $table->string('residential_barangay')->nullable()->comment('Residential Barangay');
            $table->string('residential_city')->nullable()->comment('Residential City/Municipality');
            $table->string('residential_province')->nullable()->comment('Residential Province');
            $table->string('residential_zip_code')->nullable()->comment('Residential Zip Code');
            
            $table->string('permanent_address_line_1')->nullable()->comment('Permanent House/Block/Lot No.');
            $table->string('permanent_street')->nullable()->comment('Permanent Street');
            $table->string('permanent_village')->nullable()->comment('Permanent Subdivision/Vilalge');
            $table->string('permanent_barangay')->nullable()->comment('Permanent Barangay');
            $table->string('permanent_city')->nullable()->comment('Permanent City/Municipality');
            $table->string('permanent_province')->nullable()->comment('Permanent Province');
            $table->string('permanent_zip_code')->nullable()->comment('Permanent Zip Code');

            $table->string('telephone_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_personal_info_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'suffix', 'desc' => 'Extension name'],
            ['name' => 'height', 'desc' => 'Height in meters'],
            ['name' => 'weight', 'desc' => 'Weight in killograms'],
            ['name' => 'agency_employee_no', 'desc' => 'Agency Employee No.'],
            ['name' => 'citizenship_type', 'desc' => 'Either Single or Dual Citizenship'],
            ['name' => 'citizenship_process', 'desc' => 'Either by Birth or by Naturalization'],
            ['name' => 'primary_country', 'desc' => 'Primary Citizenship Country'],
            ['name' => 'secondary_country', 'desc' => 'For Dual Citizenship'],
            ['name' => 'residential_address_line_1', 'desc' => 'Residential House/Block/Lot No.'],
            ['name' => 'residential_street', 'desc' => 'Residential Street'],
            ['name' => 'residential_village', 'desc' => 'Residential Subdivision/Vilalge'],
            ['name' => 'residential_barangay', 'desc' => 'Residential Barangay'],
            ['name' => 'residential_city', 'desc' => 'Residential City/Municipality'],
            ['name' => 'residential_province', 'desc' => 'Residential Province'],
            ['name' => 'residential_zip_code', 'desc' => 'Residential Zip Code'],
            ['name' => 'permanent_address_line_1', 'desc' => 'Permanent House/Block/Lot No.'],
            ['name' => 'permanent_street', 'desc' => 'Permanent Street'],
            ['name' => 'permanent_village', 'desc' => 'Permanent Subdivision/Vilalge'],
            ['name' => 'permanent_barangay', 'desc' => 'Permanent Barangay'],
            ['name' => 'permanent_city', 'desc' => 'Permanent City/Municipality'],
            ['name' => 'permanent_province', 'desc' => 'Permanent Province'],
            ['name' => 'permanent_zip_code', 'desc' => 'Permanent Zip Code'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_personal_info_audits');
    }
}
