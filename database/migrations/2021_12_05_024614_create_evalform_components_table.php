<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalformComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evalform_components', function (Blueprint $table) {
            $table->id('eval_form_component_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Form where the component is included');
            $table->text('title')->comment('Component Title');
            $table->double('percentage', 5, 2)->comment('Percentage equivalent of the component');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_components', [
            ['name' => 'eval_form_id', 'desc' => 'Form where the component is included'],
            ['name' => 'title', 'desc' => 'Component Title'],
            ['name' => 'percentage', 'desc' => 'Percentage equivalent of the component'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evalform_components');
    }
}
