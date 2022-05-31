<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillaAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plantilla_audits', function (Blueprint $table) {
            $table->id('plantilla_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('plantilla_id');
            $table->boolean('permanent')->default(false)->comment('Is it Permanent Plantilla or Project Plantilla');
            $table->unsignedBigInteger('department')->nullable()->comment('Deparment for Permanent Plantilla');
            $table->unsignedBigInteger('project')->nullable()->comment('Project for Project Plantilla');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Position needed');
            $table->unsignedBigInteger('employment_status')->nullable()->comment('Employment Status needed');
            $table->unsignedInteger('total_items')->default(1)->comment('Fixed 1 for Permanent Plantilla. Any for Project Plantilla.');

            $table->unsignedBigInteger('salary_grade')->nullable()->comment('Salaray Grade for the item');
            $table->unsignedBigInteger('step_increment')->nullable()->comment('Step Increment of the Salary Grade');
            $table->double('salary', 14, 2)->default(0);
            $table->text('salary_in_word')->nullable();
            $table->unsignedBigInteger('salary_per')->nullable()->comment('Salary per? per year, per month, or per day');

            $table->string('memorandum')->default('CSC MC No. 24 s. 2017')->comment('Memorandum at Certification of Appointment Docu Page 2');
            $table->string('published_at')->nullable()->comment('Published at at Certification of Appointment Docu Page 2');
            $table->string('published_date_from')->nullable()->comment('Published date from at Certification of Appointment Docu Page 2');
            $table->string('published_date_to')->nullable()->comment('Published date to at Certification of Appointment Docu Page 2');
            $table->string('posted_at')->nullable()->comment('Posted at at Certification of Appointment Docu page 2');
            $table->string('posted_date_from')->nullable()->comment('Published date from at Certification of Appointment Docu Page 2');
            $table->string('posted_date_to')->nullable()->comment('Published date to at Certification of Appointment Docu Page 2');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_plantilla_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'permanent', 'desc' => 'Is it Permanent Plantilla or Project Plantilla'],
            ['name' => 'department', 'desc' => 'Deparment for Permanent Plantilla'],
            ['name' => 'project', 'desc' => 'Project for Project Plantilla'],
            ['name' => 'position_id', 'desc' => 'Position needed'],
            ['name' => 'employment_status', 'desc' => 'Employment Status needed'],
            ['name' => 'total_items', 'desc' => 'Fixed 1 for Permanent Plantilla. Any for Project Plantilla.'],
            ['name' => 'salary_grade', 'desc' => 'Salaray Grade for the item'],
            ['name' => 'step_increment', 'desc' => 'Step Increment of the Salary Grade'],
            ['name' => 'salary_per', 'desc' => 'Salary per? per year, per month, or per day'],
            ['name' => 'memorandum', 'desc' => 'Memorandum at Certification of Appointment Docu Page 2'],
            ['name' => 'published_at', 'desc' => 'Published at at Certification of Appointment Docu Page 2'],
            ['name' => 'published_date_from', 'desc' => 'Published date from at Certification of Appointment Docu Page 2'],
            ['name' => 'published_date_to', 'desc' => 'Published date to at Certification of Appointment Docu Page 2'],
            ['name' => 'posted_at', 'desc' => 'Posted at at Certification of Appointment Docu page 2'],
            ['name' => 'posted_date_from', 'desc' => 'Published date from at Certification of Appointment Docu Page 2'],
            ['name' => 'posted_date_to', 'desc' => 'Published date to at Certification of Appointment Docu Page 2'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plantilla_audits');
    }
}
