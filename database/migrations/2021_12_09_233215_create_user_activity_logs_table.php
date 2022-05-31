<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_activity_logs', function (Blueprint $table) {
            $table->id('user_activity_log_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('logged_at')->useCurrent();
            $table->string('type');
            $table->text('action')->nullable()->comment('Descriptive log with the id of specified resource.');
            $table->text('remarks')->nullable()->comment('Result of the action.');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_user_activity_logs', [
            ['name' => 'action', 'desc' => 'Descriptive log with the id of specified resource.'],
            ['name' => 'remarks', 'desc' => 'Result of the action.'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_activity_logs');
    }
}
