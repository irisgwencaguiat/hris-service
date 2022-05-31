<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAnswerAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_answer_audits', function (Blueprint $table) {
            $table->id('evaluation_answer_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evaluation_answer_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Where belongs in evaluations');
            $table->text('comment')->nullable()->comment('Comments (if givng a rating of 5: indicate actual scenarios when a particular behavious was observed)');
            $table->unsignedInteger('quality')->default(0)->comment('Q');
            $table->unsignedInteger('efficiency')->default(0)->comment('E');
            $table->unsignedInteger('timeliness')->default(0)->comment('T');
            $table->unsignedDouble('average')->default(0)->comment('A');
            $table->text('remarks')->nullable()->comment('Remarks');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answer_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'evaluation_id', 'desc' => 'Where belongs in evaluations'],
            ['name' => 'comment', 'desc' => 'Comments (if giving a rating of 5: indicate actual scenarios when a particular behavious was observed)'],
            ['name' => 'quality', 'desc' => 'Q'],
            ['name' => 'efficiency', 'desc' => 'E'],
            ['name' => 'timeliness', 'desc' => 'T'],
            ['name' => 'average', 'desc' => 'A'],
            ['name' => 'remarks', 'desc' => 'Remarks'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evaluation_answer_audits');
    }
}
