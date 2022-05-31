<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalformSuccessIndicatorAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evalform_success_indicator_audits', function (Blueprint $table) {
            $table->id('evalform_success_indicator_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evalform_success_indicator_id');
            $table->unsignedBigInteger('evalform_major_final_output_id')->comment('Where success indicator included');
            $table->text('desc')->nullable();
            $table->boolean('has_actual_accomplishment_displaytext')->deafult(false)->comment('if the success indicator already have display text for accomplishments');
            $table->text('actual_accomplishment_displaytext')->nullable()->comment('Actual Accomplishment Display Text');
            $table->boolean('need_comment')->nullable(true)->comment('Evaluator need to add evaluatee comments');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_success_indicator_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'evalform_major_final_output_id', 'desc' => 'Where success indicator included'],
            ['name' => 'has_actual_accomplishment_displaytext', 'desc' => 'if the success indicator already have display text for accomplishments'],
            ['name' => 'actual_accomplishment_displaytext', 'desc' => 'Actual Accomplishment Display Text'],
            ['name' => 'need_comment', 'desc' => 'Evaluator need to add evaluatee comments'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evalform_success_indicator_audits');
    }
}
