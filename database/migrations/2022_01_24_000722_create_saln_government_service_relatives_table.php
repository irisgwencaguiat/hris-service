<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalnGovernmentServiceRelativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_saln_government_service_relatives', function (
            Blueprint $table
        ) {
            $table->id('saln_government_service_relative_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('relative_name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('position')->nullable();
            $table->string('agency_office_name')->nullable();
            $table->string('agency_office_address')->nullable();

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
        Schema::dropIfExists('tbl_saln_government_service_relatives');
    }
}
