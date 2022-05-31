<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsGovernmentIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_government_identifications', function (
            Blueprint $table
        ) {
            $table->id('pds_government_identification_id');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('employee_id');
            $table->string('issued_id');
            $table->string('id_no');
            $table->date('issuance_date')->nullable();
            $table->string('issuance_place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     d*
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_government_identifications');
    }
}
