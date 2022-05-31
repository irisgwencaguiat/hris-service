<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_taxes', function (Blueprint $table) {
            $table->id('tax_id');
            $table->string('tax_desc')->unique();
            $table->double('fixed_rate', 8, 4)->nullable();
            $table->double('fixed_amount', 14, 2)->nullable();
            $table->boolean('has_reference_table')->deafult(false);
            $table->string('reference_table')->nullable();
            $table->boolean('activated')->default(true);
            
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
        Schema::dropIfExists('tbl_taxes');
    }
}
