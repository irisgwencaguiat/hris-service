<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillaItemAppointmentAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plantilla_item_appointment_audits', function (Blueprint $table) {
            $table->id('plantilla_item_appointment_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('plantilla_item_appointment_id');
            $table->boolean('approved')->default(false)->comment('If the appointment is approved');

            $table->unsignedBigInteger('plantilla_id');
            $table->unsignedBigInteger('plantilla_item_id');
            $table->string('plantilla_item_no')->nullable()->comment('Plantilla Item No. Unique for Permanent Plantilla. Null for Project Planilla.');

            $table->boolean('permanent')->default(false)->comment('Is it Permanent Plantilla or Project Plantilla');
            $table->unsignedBigInteger('department')->nullable()->comment('Deparment for Permanent Plantilla');
            $table->unsignedBigInteger('project')->nullable()->comment('Project for Project Plantilla');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Position needed');

            $table->unsignedBigInteger('salary_grade')->nullable()->comment('Salaray Grade for the item');
            $table->unsignedBigInteger('step_increment')->nullable()->comment('Step Increment of the Salary Grade');
            $table->double('salary', 14, 2)->default(0);
            $table->text('salary_in_word')->nullable();
            $table->unsignedBigInteger('salary_per')->nullable()->comment('Salary per? per year, per month, or per day');
            $table->double('salary_per_annum', 14, 2)->default(0)->comment('Salary per Annum');
            $table->double('lumpsum_wage', 14, 2)->default(0)->comment('Lumpsum wage');
            
            $table->date('contract_date_from')->nullable()->comment('Period of Contract, Date From');
            $table->date('contract_date_to')->nullable()->comment('Period of Contract, Date To');

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('employment_status')->nullable()->comment('Employment Status/Appointment Status');
            $table->unsignedBigInteger('appointment_nature')->nullable();
            
            $table->unsignedBigInteger('last_owner')->nullable()->comment('Last owner of the item');
            $table->unsignedBigInteger('last_owner_dismissal_type')->nullable()->comment('Dismissal type of the last owner');
            
            $table->date('probationary_date_start')->nullable()->comment('For Probation. Probationary Period Date Start');
            $table->date('probationary_date_end')->nullable()->comment('For Probation. Probationary Period Date End');
            $table->unsignedBigInteger('city_mayor')->nullable()->comment('City Mayor');
            $table->date('date_of_signing')->nullable()->comment('Date of Signing');

            $table->unsignedBigInteger('authorized_official')->nullable()->comment('Authorized Officail');
            $table->date('csc_action_date')->nullable()->comment('CSC Action Date');
            $table->text('csc_action_stamp_path')->nullable()->comment('CSC Action Stamp of Date of Release');

            $table->string('memorandum')->default('CSC MC No. 24 s. 2017')->comment('Memorandum at Certification of Appointment Docu Page 2');
            $table->string('published_at')->nullable()->comment('Published at at Certification of Appointment Docu Page 2');
            $table->string('published_date_from')->nullable()->comment('Published date from at Certification of Appointment Docu Page 2');
            $table->string('published_date_to')->nullable()->comment('Published date to at Certification of Appointment Docu Page 2');
            $table->string('posted_at')->nullable()->comment('Posted at at Certification of Appointment Docu page 2');
            $table->string('posted_date_from')->nullable()->comment('Published date from at Certification of Appointment Docu Page 2');
            $table->string('posted_date_to')->nullable()->comment('Published date to at Certification of Appointment Docu Page 2');
            $table->unsignedBigInteger('city_hrm_officer')->nullable()->comment('City HRM Officer');

            $table->date('qualified_at')->nullable()->comment('Screened and found qualified date');
            $table->unsignedBigInteger('hrmpsb_chairperson')->nullable()->comment('HRMPSB Chairperson');

            $table->text('csc_notation')->nullable()->comment('CSC Notation');
            $table->date('appointed_at')->nullable()->comment('Acknowledgement, Apponted on');
            $table->unsignedBigInteger('appointed_by')->nullable()->comment('Acknowledgement, Appointee');

            $table->string('department_branch')->nullable()->comment('Branch at Service Record docu');
            $table->unsignedBigInteger('previous_plantilla_item_id')->nullable()->comment('Previous Plantilla item');
            
            $table->unsignedBigInteger('city_administrator')->nullable()->comment('For JO Contract. City Administrator');
            $table->unsignedBigInteger('city_budget_department')->nullable()->comment('For JO Contract. City Budget Department');
            $table->unsignedBigInteger('city_treasurer')->nullable()->comment('For JO Contract. City Treasurer');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_plantilla_item_appointment_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'approved', 'desc' => 'If the appointment is approved'],
            ['name' => 'plantilla_item_no', 'desc' => 'Plantilla Item No. Unique for Permanent Plantilla. Null for Project Planilla.'],
            ['name' => 'permanent', 'desc' => 'Is it Permanent Plantilla or Project Plantilla'],
            ['name' => 'department', 'desc' => 'Deparment for Permanent Plantilla'],
            ['name' => 'project', 'desc' => 'Project for Project Plantilla'],
            ['name' => 'position_id', 'desc' => 'Position needed'],
            ['name' => 'salary_grade', 'desc' => 'Salaray Grade for the item'],
            ['name' => 'step_increment', 'desc' => 'Step Increment of the Salary Grade'],
            ['name' => 'salary_per', 'desc' => 'Salary per? per year, per month, or per day'],
            ['name' => 'salary_per_annum', 'desc' => 'Salary per Annum'],
            ['name' => 'lumpsum_wage', 'desc' => 'Lumpsum wage'],
            ['name' => 'contract_date_from', 'desc' => 'Period of Contract, Date From'],
            ['name' => 'contract_date_to', 'desc' => 'Period of Contract, Date To'],
            ['name' => 'employment_status', 'desc' => 'Employment Status/Appointment Status'],
            ['name' => 'last_owner', 'desc' => 'Last owner of the item'],
            ['name' => 'last_owner_dismissal_type', 'desc' => 'Dismissal type of the last owner'],
            ['name' => 'probationary_date_start', 'desc' => 'For Probation. Probationary Period Date Start'],
            ['name' => 'probationary_date_end', 'desc' => 'For Probation. Probationary Period Date End'],
            ['name' => 'city_mayor', 'desc' => 'City Mayor'],
            ['name' => 'date_of_signing', 'desc' => 'Date of Signing'],
            ['name' => 'authorized_official', 'desc' => 'Authorized Officail'],
            ['name' => 'csc_action_date', 'desc' => 'CSC Action Date'],
            ['name' => 'csc_action_stamp_path', 'desc' => 'CSC Action Stamp of Date of Release'],
            ['name' => 'memorandum', 'desc' => 'Memorandum at Certification of Appointment Docu Page 2'],
            ['name' => 'published_at', 'desc' => 'Published at at Certification of Appointment Docu Page 2'],
            ['name' => 'published_date_from', 'desc' => 'Published date from at Certification of Appointment Docu Page 2'],
            ['name' => 'published_date_to', 'desc' => 'Published date to at Certification of Appointment Docu Page 2'],
            ['name' => 'posted_at', 'desc' => 'Posted at at Certification of Appointment Docu page 2'],
            ['name' => 'posted_date_from', 'desc' => 'Published date from at Certification of Appointment Docu Page 2'],
            ['name' => 'posted_date_to', 'desc' => 'Published date to at Certification of Appointment Docu Page 2'],
            ['name' => 'city_hrm_officer', 'desc' => 'City HRM Officer'],
            ['name' => 'qualified_at', 'desc' => 'Screened and found qualified date'],
            ['name' => 'hrmpsb_chairperson', 'desc' => 'HRMPSB Chairperson'],
            ['name' => 'csc_notation', 'desc' => 'CSC Notation'],
            ['name' => 'appointed_at', 'desc' => 'Acknowledgement, Apponted on'],
            ['name' => 'appointed_by', 'desc' => 'Acknowledgement, Appointee'],
            ['name' => 'department_branch', 'desc' => 'Branch at Service Record docu'],
            ['name' => 'previous_plantilla_item_id', 'desc' => 'Previous Plantilla item'],
            ['name' => 'city_administrator', 'desc' => 'For JO Contract. City Administrator'],
            ['name' => 'city_budget_department', 'desc' => 'For JO Contract. City Budget Department'],
            ['name' => 'city_treasurer', 'desc' => 'For JO Contract. City Treasurer'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plantilla_item_appointment_audits');
    }
}
