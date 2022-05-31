<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evaluation_schedules', function (Blueprint $table) {
            $table->id('eval_sched_id');
            $table->string('eval_type', 10)->comment('Evaluation Type');
            $table->string('eval_index')->unique()->comment('Unique and displayable index for evaluation period');
            $table->date('eval_display_start')->nullable()->comment('Starting date of the overall evaluation');
            $table->date('eval_display_end')->nullable()->comment('Ending Date of the overall evaluation');
            $table->date('date_start')->nullable()->comment('Starting date for the evaluators to evaluate');
            $table->date('date_end')->nullable()->comment('Ending date for the evaluators to evaluate');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_schedules', [
            ['name' => 'eval_type', 'desc' => 'Evaluation Type'],
            ['name' => 'eval_index', 'desc' => 'Unique and displayable index for evaluation period'],
            ['name' => 'eval_display_start', 'desc' => 'Starting date of the overall evaluation'],
            ['name' => 'eval_display_end', 'desc' => 'Ending Date of the overall evaluation'],
            ['name' => 'date_start', 'desc' => 'Starting date for the evaluators to evaluate'],
            ['name' => 'date_end', 'desc' => 'Ending date for the evaluators to evaluate'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evaluation_schedules');
    }
}
