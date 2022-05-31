<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_cities', function (Blueprint $table) {
            $table->string('city_code', 6)->primary();
            $table->string('city_name', 100)->nullable();
            $table->string('reg_code', 2)->nullable();
            $table->string('prov_code', 4)->nullable();
            $table->string('nscb_city_code')->nullable();
            $table->string('nscb_city_name')->nullable();
            $table->unsignedBigInteger('city_classification')->default(3)->comment('Referenced to City Classifications');
            $table->boolean('chartered')->default(false);

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
        Schema::dropIfExists('ref_cities');
    }
}
