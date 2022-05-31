<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainLawAffectedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_train_law_affecteds', function (Blueprint $table) {
            $table->id('train_law_affected_id');
            $table->unsignedBigInteger('train_law_id');
            $table->unsignedBigInteger('employment_status_id');
            $table->unsignedBigInteger('position_id');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('train_law_id')
                ->references('train_law_id')
                ->on('tbl_train_laws');
                
            $table->foreign('employment_status_id')
                ->references('employment_status_id')
                ->on('ref_employment_statuses');
                
            $table->foreign('position_id')
                ->references('position_id')
                ->on('ref_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_train_law_affecteds');
    }
}
