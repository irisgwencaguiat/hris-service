<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_provinces', function (Blueprint $table) {
            $table->string('prov_code', 4)->primary();
            $table->string('prov_name', 100)->nullable();
            $table->string('reg_code', 2)->nullable();
            $table->string('nscb_prov_code', 4)->nullable();
            $table->string('nscb_prov_name', 100)->nullable();
            
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
        Schema::dropIfExists('ref_provinces');
    }
}
