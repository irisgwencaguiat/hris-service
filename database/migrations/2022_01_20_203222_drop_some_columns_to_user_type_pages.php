<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSomeColumnsToUserTypePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_user_type_pages', function (Blueprint $table) {
            $table->dropColumn('route_name');
            $table->dropColumn('page_icon');

            $table->after('activated_at', function ($table) {
                $table->unsignedBigInteger('deactivated_by')->nullable();
                $table->timestamp('deactivated_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_user_type_pages', function (Blueprint $table) {
            //
        });
    }
}
