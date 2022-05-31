<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillaItemAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plantilla_item_audits', function (Blueprint $table) {
            $table->id('plantilla_item_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('plantilla_item_id');
            $table->unsignedBigInteger('plantilla_id');

            $table->string('plantilla_item_no')->nullable()->comment('Plantilla Item No. Unique for Permanent Plantilla. Null for Project Planilla.');

            $table->unsignedBigInteger('plantilla_item_appointment_id')->nullable()->comment('Plantilla Item Appointment Details');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('employment_status')->nullable()->comment('Employment Status/Appointment Status');
            $table->unsignedBigInteger('appointment_nature')->nullable();

            $table->unsignedBigInteger('salary_grade')->nullable()->comment('Salaray Grade for the item');
            $table->unsignedBigInteger('step_increment')->nullable()->comment('Step Increment of the Salary Grade');
            $table->double('salary', 14, 2)->default(0);
            $table->text('salary_in_word')->nullable();
            $table->unsignedBigInteger('salary_per')->nullable()->comment('Salary per? per year, per month, or per day');
            
            $table->date('contract_date_from')->nullable()->comment('Period of Contract, Date From');
            $table->date('contract_date_to')->nullable()->comment('Period of Contract, Date To');
            
            $table->unsignedBigInteger('last_owner')->nullable()->comment('Last owner of the item');
            $table->unsignedBigInteger('last_owner_dismissal_type')->nullable()->comment('Dismissal type of the last owner');

            $table->unsignedBigInteger('appointed_by')->nullable()->comment('Acknowledgement, Appointee');
            $table->date('appointed_at')->nullable()->comment('Acknowledgement, Apponted on');

        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_plantilla_item_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'plantilla_item_no', 'desc' => 'Plantilla Item No. Unique for Permanent Plantilla. Null for Project Planilla.'],
            ['name' => 'plantilla_item_appointment_id', 'desc' => 'Plantilla Item Appointment Details'],
            ['name' => 'employment_status', 'desc' => 'Employment Status/Appointment Status'],
            ['name' => 'salary_grade', 'desc' => 'Salaray Grade for the item'],
            ['name' => 'step_increment', 'desc' => 'Step Increment of the Salary Grade'],
            ['name' => 'salary_per', 'desc' => 'Salary per? per year, per month, or per day'],
            ['name' => 'contract_date_from', 'desc' => 'Period of Contract, Date From'],
            ['name' => 'contract_date_to', 'desc' => 'Period of Contract, Date To'],
            ['name' => 'last_owner', 'desc' => 'Last owner of the item'],
            ['name' => 'last_owner_dismissal_type', 'desc' => 'Dismissal type of the last owner'],
            ['name' => 'appointed_by', 'desc' => 'Acknowledgement, Appointee'],
            ['name' => 'appointed_at', 'desc' => 'Acknowledgement, Apponted on'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plantilla_item_audits');
    }
}
