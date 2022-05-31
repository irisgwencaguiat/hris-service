<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefBarangaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_barangays', function (Blueprint $table) {
            $table->string('bgy_code', 9)->primary();
            $table->string('bgy_name', 100)->nullable();
            $table->string('reg_code', 2)->nullable();
            $table->string('prov_code', 4)->nullable();
            $table->string('city_code', 6)->nullable();
            $table->string('nscb_bgy_code', 9)->nullable();
            $table->string('nscb_bgy_name', 100)->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_barangays');
    }
}
