<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_forms', function (Blueprint $table) {
            $table->id('eval_form_id');
            $table->unsignedBigInteger('eval_sched_id')->comment('Reference to evaluation schedule. There must be one form for eval sched/index');
            $table->string('eval_type')->comment('Evaluation Type');
            $table->string('form_title')->comment('Title of the form');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_forms', [
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
        Schema::dropIfExists('tbl_evaluation_forms');
    }
}
