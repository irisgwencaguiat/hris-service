<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivationAtOnEvaluationSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_evaluation_schedules', function (Blueprint $table) {
            $table->timestamp('activated_at')->after('deactivated_by')->nullable();
            $table->timestamp('deactivated_at')->after('activated_at')->nullable();
        });

        Schema::table('tbl_evaluation_schedule_audits', function (Blueprint $table) {
            $table->timestamp('activated_at')->after('deactivated_by')->nullable();
            $table->timestamp('deactivated_at')->after('activated_at')->nullable();
        });
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
