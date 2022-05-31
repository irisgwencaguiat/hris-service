<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProfileAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_profile_audits', function (Blueprint $table) {
            $table->id('employee_profile_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('employee_id');

            $table->string('employee_no')->unique()->nullable();
            $table->string('email')->unique();
            $table->text('complete_name')->nullable();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('suffix', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('sex', 10)->nullable();
            $table->string('civil_status', 10);
            $table->double('height', 6, 2)->nullable()->comment('Height in meters');
            $table->double('weight', 6, 2)->nullable()->comment('Weight in killograms');
            $table->string('blood_type', 10)->nullable();

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

            $table->boolean('pwd')->default(false)->comment('Person with disability. Connected to PDS Other Info Question table, column question 40 B.');
            $table->boolean('solo_parent')->default(false)->comment('Solo Parent. Connected to PDS Other Info Question table, column question 40 C.');

            $table->text('photo_path')->nullable();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_profile_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'height', 'desc' => 'Height in meters'],
            ['name' => 'weight', 'desc' => 'Weight in killograms'],
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
            ['name' => 'pwd', 'desc' => 'Person with disability. Connected to PDS Other Info Question table, column question 40 B.'],
            ['name' => 'solo_parent', 'desc' => 'Solo Parent. Connected to PDS Other Info Question table, column question 40 C.'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_profile_audits');
    }
}
