<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPdsVoluntaryWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_voluntary_works', function (Blueprint $table) {
            $table->dropColumn('organization');
            $table->string('organization_name');
            $table->string('organization_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_pds_voluntary_works', function (Blueprint $table) {
            $table
                ->text('organization')
                ->comment('Name & Address of Organization');
            $table->dropColumn('organization_name');
            $table->dropColumn('organization_address');
        });
    }
}
