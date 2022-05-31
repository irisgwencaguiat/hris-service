<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemodelUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('suffix');
            $table->dropColumn('user_type');
            $table->dropColumn('user_classification');
            $table->dropColumn('user_ws_key');
            $table->dropColumn('user_e_key');

            $table->boolean('activated')->default(true);
            $table->unsignedBigInteger('activated_by')->nullable();
            $table->unsignedBigInteger('deactivated_by')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();

            $table->boolean('banned')->default(false);
            $table->unsignedBigInteger('banned_by')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->longText('banned_reason')->nullable();

            $table->boolean('expired')->default(false);
            $table->unsignedBigInteger('expired_by')->nullable()->comment('The one that sets the expiry date.');
            $table->timestamp('expired_at')->nullable();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_users', [
            ['name' => 'expired_by', 'desc' => 'The one that sets the expiry date.'],
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
