<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameForeignKeysAtUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_user_profiles', function (Blueprint $table) {
            $table->renameColumn('user_type', 'user_type_id');
            $table->renameColumn('user_classification', 'user_classification_id');
        });

        // Audit
        Schema::table('tbl_user_profile_audits', function (Blueprint $table) {
            $table->renameColumn('user_type', 'user_type_id');
            $table->renameColumn('user_classification', 'user_classification_id');
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
