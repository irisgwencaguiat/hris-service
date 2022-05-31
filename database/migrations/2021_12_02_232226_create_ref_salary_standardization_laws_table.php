<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefSalaryStandardizationLawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_salary_standardization_laws', function (Blueprint $table) {
            $table->id('salary_standardization_law_id');    
            $table->string('law_name')->nullable();
            $table->text('law_desc')->nullable();
            $table->date('effective_date_from')->useCurrent();
            $table->date('effective_date_to')->nullable();
            $table->boolean('active')->default(false);

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
        Schema::dropIfExists('ref_salary_standardization_laws');
    }
}
