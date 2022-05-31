<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefStepIncrementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_step_increments', function (Blueprint $table) {
            $table->id('step_increment_id');
            $table->unsignedBigInteger('salary_grade_id')->nullable();
            $table->unsignedInteger('number')->comment('Number in step');
            $table->string('name')->nullable()->comment('Name of the step with number (ex. SI2)');
            $table->double('salary', 14, 2)->default(0);

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'ref_step_increments', [
            ['name' => 'number', 'desc' => 'Number in step'],
            ['name' => 'name', 'desc' => 'Name of the step with number (ex. SI2)'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_step_increments');
    }
}
