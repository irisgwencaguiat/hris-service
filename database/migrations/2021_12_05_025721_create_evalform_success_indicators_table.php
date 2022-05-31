<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalformSuccessIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evalform_success_indicators', function (Blueprint $table) {
            $table->id('evalform_success_indicator_id');
            $table->unsignedBigInteger('evalform_major_final_output_id')->comment('Where success indicator included');
            $table->text('desc')->nullable();
            $table->boolean('has_actual_accomplishment_displaytext')->deafult(false)->comment('if the success indicator already have display text for accomplishments');
            $table->text('actual_accomplishment_displaytext')->nullable()->comment('Actual Accomplishment Display Text');
            $table->boolean('need_comment')->nullable(true)->comment('Evaluator need to add evaluatee comments');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_success_indicators', [
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
        Schema::dropIfExists('tbl_evalform_success_indicators');
    }
}
