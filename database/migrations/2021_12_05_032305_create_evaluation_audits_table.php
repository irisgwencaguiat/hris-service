<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_audits', function (Blueprint $table) {
            $table->id('evaluation_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('eval_sched_id')->comment('Period when the evaluation occured');
            $table->string('eval_type', 10)->comment('Evaluation Type (IPCR, OPCR)');
            $table->unsignedBigInteger('eval_form_id')->comment('Evaluation Form used');
            $table->unsignedBigInteger('evaluator')->comment('Employee that evaluates (Immediate Supervisor, HR Head)');
            $table->unsignedBigInteger('evaluatee_as_employee')->comment('For IPCR, employee as evaluatee');
            $table->unsignedBigInteger('evaluatee_as_department')->comment('For OPCR, department as evaluatee');
            $table->timestamp('evaluated_at')->useCurrent();
            $table->double('average', 8, 2)->default(0)->comment('Total Average of each evaluation');
            $table->string('rating')->nullable();
            $table->text('development_purpose_comments')->nullable()->comment('Comments and Recommendation for Development Purposes');

            $table->unsignedBigInteger('approved_by')->nullable()->comment('Approved by at the top of the form');
            $table->unsignedBigInteger('approved_at')->nullable()->comment('Approved date at the top of the form');

            $table->unsignedBigInteger('evaluator_position')->nullable()->comment('Evaluator Position for the footer. Also to view the report exactly at it is even when the employee changed position');
            $table->unsignedBigInteger('evaluator_department')->nullable()->comment('Evaluator Department for the footer. Also to view the report exactly at it is even when the employee changed department');
            $table->unsignedBigInteger('evaluatee_position')->nullable()->comment('Evaluatee Position for the footer. Also to view the report exactly at it is even when the employee changed position');
            $table->unsignedBigInteger('evaluatee_department')->nullable()->comment('Evaluatee Department for the footer. Also to view the report exactly at it is even when the employee changed department');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'eval_sched_id', 'desc' => 'Period when the evaluation occured'],
            ['name' => 'eval_type', 'desc' => 'Evaluation Type (IPCR, OPCR)'],
            ['name' => 'eval_form_id', 'desc' => 'Evaluation Form used'],
            ['name' => 'evaluator', 'desc' => 'Employee that evaluates (Immediate Supervisor, HR Head)'],
            ['name' => 'evaluatee_as_employee', 'desc' => 'For IPCR, employee as evaluatee'],
            ['name' => 'evaluatee_as_department', 'desc' => 'For OPCR, department as evaluatee'],
            ['name' => 'average', 'desc' => 'Total Average of each evaluation'],
            ['name' => 'development_purpose_comments', 'desc' => 'Comments and Recommendation for Development Purposes'],
            ['name' => 'approved_by', 'desc' => 'Approved by at the top of the form'],
            ['name' => 'approved_at', 'desc' => 'Approved date at the top of the form'],
            ['name' => 'evaluator_position', 'desc' => 'Evaluator Position for the footer. Also to view the report exactly at it is even when the employee changed position'],
            ['name' => 'evaluator_department', 'desc' => 'Evaluator Department for the footer. Also to view the report exactly at it is even when the employee changed department'],
            ['name' => 'evaluatee_position', 'desc' => 'Evaluatee Position for the footer. Also to view the report exactly at it is even when the employee changed position'],
            ['name' => 'evaluatee_department', 'desc' => 'Evaluatee Department for the footer. Also to view the report exactly at it is even when the employee changed department'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evaluation_audits');
    }
}
