<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAnswerComponentAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_answer_component_audits', function (Blueprint $table) {
            $table->id('evaluation_answer_component_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evaluation_answer_component_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Reference to evaluations');
            $table->unsignedBigInteger('evalform_component_id')->comment('Reference to eval form components');
            $table->unsignedDouble('subtotal')->default(0)->comment('Average of scores per component');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answer_component_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'evaluation_id', 'desc' => 'Reference to evaluations'],
            ['name' => 'evalform_component_id', 'desc' => 'Reference to eval form components'],
            ['name' => 'subtotal', 'desc' => 'Average of scores per component'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evaluation_answer_component_audits');
    }
}
