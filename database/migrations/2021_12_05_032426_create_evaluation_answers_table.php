<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_answers', function (Blueprint $table) {
            $table->id('evaluation_answer_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Where belongs in evaluations');
            $table->text('comment')->nullable()->comment('Comments (if givng a rating of 5: indicate actual scenarios when a particular behavious was observed)');
            $table->unsignedInteger('quality')->default(0)->comment('Q');
            $table->unsignedInteger('efficiency')->default(0)->comment('E');
            $table->unsignedInteger('timeliness')->default(0)->comment('T');
            $table->unsignedDouble('average')->default(0)->comment('A');
            $table->text('remarks')->nullable()->comment('Remarks');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answers', [
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
        Schema::dropIfExists('tbl_evaluation_answers');
    }
}
