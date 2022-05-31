<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema =Schema::create('tbl_user_audits', function (Blueprint $table) {
            $table->id('user_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->string('username', 100);
            $table->string('password', 100);

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
        sqlsrvAddTableColumDescs('dbo', 'tbl_user_audits', [
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
        Schema::dropIfExists('tbl_user_audits');
    }
}
