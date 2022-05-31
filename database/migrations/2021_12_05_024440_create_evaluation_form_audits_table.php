<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationFormAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_form_audits', function (Blueprint $table) {
            $table->id('evaluation_form_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();
            
            $table->unsignedBigInteger('eval_form_id');
            $table->unsignedBigInteger('eval_sched_id')->comment('Reference to evaluation schedule. There must be one form for eval sched/index');
            $table->string('eval_type')->comment('Evaluation Type');
            $table->string('form_title')->comment('Title of the form');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_form_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'eval_sched_id', 'desc' => 'Reference to evaluation schedule. There must be one form for eval sched/index'],
            ['name' => 'eval_type', 'desc' => 'Evaluation Type'],
            ['name' => 'form_title', 'desc' => 'Title of the form'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evaluation_form_audits');
    }
}
