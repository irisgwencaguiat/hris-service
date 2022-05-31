<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalformMajorFinalOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evalform_major_final_outputs', function (Blueprint $table) {
            $table->id('evalform_major_final_output_id');
            $table->unsignedBigInteger('evalform_component_id')->comment('Where major final output belongs');
            $table->text('desc')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_major_final_outputs', [
            ['name' => 'evalform_component_id', 'desc' => 'Where major final output belongs'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evalform_major_final_outputs');
    }
}
