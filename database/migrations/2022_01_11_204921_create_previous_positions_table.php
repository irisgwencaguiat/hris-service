<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviousPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_previous_positions', function (Blueprint $table) {
            $table->id('previous_position_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('position_id');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table
                ->foreign('employee_id')
                ->references('employee_id')
                ->on('tbl_employees');

            $table
                ->foreign('position_id')
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
        Schema::dropIfExists('tbl_previous_positions');
    }
}
