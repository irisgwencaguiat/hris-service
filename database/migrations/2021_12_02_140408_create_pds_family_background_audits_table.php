<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsFamilyBackgroundAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_family_background_audits', function (Blueprint $table) {
            $table->id('pds_family_background_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('employee_id');
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_suffix')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_employer')->nullable()->comment('Employer/Business Name');
            $table->string('spouse_business_address')->nullable()->comment('Business Address');
            $table->string('spouse_telephone_no')->nullable();
            
            $table->string('father_last_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_suffix')->nullable();

            $table->string('mother_maiden_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_suffix')->nullable();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_family_background_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'spouse_employer', 'desc' => 'Employer/Business Name'],
            ['name' => 'spouse_business_address', 'desc' => 'Business Address'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_family_background_audits');
    }
}
