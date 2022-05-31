<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_service_records', function (Blueprint $table) {
            $table->id('service_record_id');
            
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->datetime('service_from')->nullable()->comment('Service Include Date from');
            $table->datetime('service_to')->nullable()->comment('Service Include Date to');

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
            $table->double('salary_per_annum', 14, 2)->default(0)->comment('Salary/A');

            $table->unsignedBigInteger('employment_status')->nullable()->comment('Employment Status/Appointment Status');
            $table->unsignedBigInteger('appointment_nature')->nullable()->comment('Remarks');

            $table->string('department_branch')->nullable();
            $table->double('leave_abs', 8, 2)->default(0);
            $table->double('vacation_abs', 8, 2)->default(0);
            $table->longText('cause')->nullable()->comment('Cause of abscense');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_service_records', [
            ['name' => 'service_from', 'desc' => 'Service Include Date from'],
            ['name' => 'service_to', 'desc' => 'Service Include Date to'],
            ['name' => 'plantilla_item_no', 'desc' => 'Plantilla Item No. Unique for Permanent Plantilla. Null for Project Planilla.'],
            ['name' => 'permanent', 'desc' => 'Is it Permanent Plantilla or Project Plantilla'],
            ['name' => 'department', 'desc' => 'Deparment for Permanent Plantilla'],
            ['name' => 'project', 'desc' => 'Project for Project Plantilla'],
            ['name' => 'position_id', 'desc' => 'Position needed'],
            ['name' => 'salary_grade', 'desc' => 'Salaray Grade for the item'],
            ['name' => 'step_increment', 'desc' => 'Step Increment of the Salary Grade'],
            ['name' => 'salary_per', 'desc' => 'Salary per? per year, per month, or per day'],
            ['name' => 'salary_per_annum', 'desc' => 'Salary/A'],
            ['name' => 'employment_status', 'desc' => 'Employment Status/Appointment Status'],
            ['name' => 'appointment_nature', 'desc' => 'Remarks'],
            ['name' => 'cause', 'desc' => 'Cause of abscense'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_service_records');
    }
}
