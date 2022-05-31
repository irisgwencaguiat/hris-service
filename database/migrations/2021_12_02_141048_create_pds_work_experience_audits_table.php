<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsWorkExperienceAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_work_experience_audits', function (Blueprint $table) {
            $table->id('pds_pds_work_experience_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pds_work_experience_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('date_from')->nullable()->comment('Inclusive Date From');
            $table->unsignedBigInteger('date_to')->nullable()->comment('Inclusive Date To');
            $table->string('position')->nullable()->comment('Position Title');
            $table->string('department')->nullable()->comment('Department/Agency/Office/Company');
            $table->double('monthly_salary', 12, 2)->default(0)->comment('Monthly Salary');
            $table->string('salary_grade')->nullable()->comment('Salary Job/Job Pay Grade (If applicable) Step (Format "00-0")');
            $table->string('employment_status')->nullable()->comment('Status of Appointment');
            $table->boolean('govt_service')->default(false)->comment('Govt Service');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_work_experience_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'date_from', 'desc' => 'Inclusive Date From'],
            ['name' => 'date_to', 'desc' => 'Inclusive Date To'],
            ['name' => 'position', 'desc' => 'Position Title'],
            ['name' => 'department', 'desc' => 'Department/Agency/Office/Company'],
            ['name' => 'monthly_salary', 'desc' => 'Monthly Salary'],
            ['name' => 'salary_grade', 'desc' => 'Salary Job/Job Pay Grade (If applicable) Step (Format "00-0")'],
            ['name' => 'employment_status', 'desc' => 'Status of Appointment'],
            ['name' => 'govt_service', 'desc' => 'Govt Service'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_work_experience_audits');
    }
}
