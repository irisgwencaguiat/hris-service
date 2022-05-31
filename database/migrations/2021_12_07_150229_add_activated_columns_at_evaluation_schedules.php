<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatedColumnsAtEvaluationSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_evaluation_schedules', function (Blueprint $table) {
            $table->boolean('activated')->default(false)->commnet('Is Activated?');
            $table->unsignedBigInteger('activated_by')->nullable()->comment('Activated By');
            $table->unsignedBigInteger('deactivated_by')->nullable()->comment('Deactivated By');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_schedules', [
            ['name' => 'activated', 'desc' => 'Is Activated?'],
            ['name' => 'activated_by', 'desc' => 'Activated By'],
            ['name' => 'deactivated_by', 'desc' => 'Deactivated By'],
        ]);

        //
        Schema::table('tbl_evaluation_schedule_audits', function (Blueprint $table) {
            $table->boolean('activated')->default(false)->commnet('Is Activated?');
            $table->unsignedBigInteger('activated_by')->nullable()->comment('Activated By');
            $table->unsignedBigInteger('deactivated_by')->nullable()->comment('Deactivated By');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_schedule_audits', [
            ['name' => 'activated', 'desc' => 'Is Activated?'],
            ['name' => 'activated_by', 'desc' => 'Activated By'],
            ['name' => 'deactivated_by', 'desc' => 'Deactivated By'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_evaluation_schedules', function (Blueprint $table) {
        });
    }
}
