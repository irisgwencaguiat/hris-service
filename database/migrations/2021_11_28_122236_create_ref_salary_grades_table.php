<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefSalaryGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_salary_grades', function (Blueprint $table) {
            $table->id('salary_grade_id');
            $table->unsignedBigInteger('salary_standardization_law_id')->nullable();
            $table->unsignedInteger('number')->nullable()->comment('Number in SG');
            $table->string('name')->nullable()->comment('SG with Number (ex. SG13)');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'ref_salary_grades', [
            ['name' => 'number', 'desc' => 'Number in SG'],
            ['name' => 'name', 'desc' => 'SG with Number (ex. SG13)'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_salary_grades');
    }
}
