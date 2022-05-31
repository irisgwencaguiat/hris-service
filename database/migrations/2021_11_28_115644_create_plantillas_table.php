<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plantillas', function (Blueprint $table) {
            $table->id('plantilla_id');
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

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_plantillas', [
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
        Schema::dropIfExists('tbl_plantillas');
    }
}
