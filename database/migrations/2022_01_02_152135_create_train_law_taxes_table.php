<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainLawTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_train_law_taxes', function (Blueprint $table) {
            $table->id('train_law_tax_id');
            $table->unsignedBigInteger('train_law_id');
            $table->double('annual_income_upper_boundary', 14, 2)->nullable();
            $table->double('annual_income_lower_boundary', 14, 2)->nullable();
            $table->boolean('lower_boundary_and_below')->default(false);
            $table->boolean('upper_boundary_and_above')->default(false);
            $table->double('tax_rate', 8, 4)->default(0.00);
            $table->double('additional_tax_amount', 14, 2)->nullable();
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('train_law_id')
                ->references('train_law_id')
                ->on('tbl_train_laws');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_train_law_taxes');
    }
}
