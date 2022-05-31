<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhilhealthRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_philhealth_rates', function (Blueprint $table) {
            $table->id('philhealth_rate_id');
            $table->unsignedInteger('year');
            $table->double('premium_rate', 8, 4)->default(0.00)->comment('In decimal.');
            $table->double('ps_rate', 8, 4)->deafult(0.50)->comment('Personal Share Rate. In decimal.');
            $table->double('gs_rate', 8, 4)->deafult(0.50)->comment('Government Share Rate. In decimal.');
            $table->boolean('activated')->default(false);
            
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
        Schema::dropIfExists('tbl_philhealth_rates');
    }
}
