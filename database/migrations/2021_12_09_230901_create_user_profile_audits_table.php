<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_profile_audits', function (Blueprint $table) {
            $table->id('user_profile_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('user_profile_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_type')->nullable()->comment('User Type');
            $table->unsignedBigInteger('user_classification')->nullable()->comment('User Classification');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix', 10)->nullable();
            $table->string('email')->nullable();
            $table->string('user_ws_key')->nullable()->comment('Authentication key for accessing APIs');
            $table->string('user_e_key')->nullable()->comment('Encryption key for APIs');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_user_profile_audits', [
            ['name' => 'user_type', 'desc' => 'User Type'],
            ['name' => 'user_classification', 'desc' => 'User Classification'],
            ['name' => 'user_ws_key', 'desc' => 'Authentication key for accessing APIs'],
            ['name' => 'user_e_key', 'desc' => 'Encryption key for APIs'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_profile_audits');
    }
}
