<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema =Schema::create('tbl_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 100)->unique();
            $table->string('password', 100);
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix', 10)->nullable();
            $table->unsignedBigInteger('user_type')->nullable()->comment('User Type');
            $table->unsignedBigInteger('user_classification')->nullable()->comment('User Classification');
            $table->string('user_ws_key')->nullable()->comment('Authentication key for accessing APIs');
            $table->string('user_e_key')->nullable()->comment('Encryption key for APIs');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_users', [
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
        Schema::dropIfExists('tbl_users');
    }
}
