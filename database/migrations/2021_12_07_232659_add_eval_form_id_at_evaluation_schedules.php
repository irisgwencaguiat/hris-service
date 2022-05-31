<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvalFormIdAtEvaluationSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_evaluation_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('eval_form_id')->after('eval_sched_id')->nullable()->comment('Referenced to Eval Foms');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_schedules', [
            ['name' => 'eval_form_id', 'desc' => 'Referenced to Eval Foms'],
        ]);

        Schema::table('tbl_evaluation_schedule_audits', function (Blueprint $table) {
            $table->unsignedBigInteger('eval_form_id')->after('eval_sched_id')->nullable()->comment('Referenced to Eval Foms');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_schedule_audits', [
            ['name' => 'eval_form_id', 'desc' => 'Referenced to Eval Foms'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
