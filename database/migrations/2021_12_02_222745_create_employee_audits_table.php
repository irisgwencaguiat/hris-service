<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_audits', function (Blueprint $table) {
            $table->id('employee_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('employee_id');

            $table->string('employee_no')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('suffix', 10)->nullable();
            $table->string('sex', 10)->nullable();
            $table->text('photo_path')->nullable();
            $table->text('esignature_path')->nullable();
            
            $table->unsignedBigInteger('department')->nullable()->comment('Department');
            $table->unsignedBigInteger('plantilla_item_id')->nullable()->comment('Current Plantilla item');
            $table->unsignedBigInteger('previous_plantilla_item_id')->nullable()->comment('Previous Plantilla item');

            $table->unsignedBigInteger('immediate_supervisor')->nullable()->comment('Employee\'s Immediate Supervisor for Evaluation in IPCR.');
            $table->boolean('is_supervisor')->default(false)->comment('Supervisor or not. For Evaluation in IPCR');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'department', 'desc' => 'Department'],
            ['name' => 'plantilla_item_id', 'desc' => 'Current Plantilla item'],
            ['name' => 'previous_plantilla_item_id', 'desc' => 'Previous Plantilla item'],
            ['name' => 'immediate_supervisor', 'desc' => 'Employees Immediate Supervisor for Evaluation in IPCR.'],
            ['name' => 'is_supervisor', 'desc' => 'Supervisor or not. For Evaluation in IPCR'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_audits');
    }
}
