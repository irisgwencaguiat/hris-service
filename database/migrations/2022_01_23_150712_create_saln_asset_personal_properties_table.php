<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalnAssetPersonalPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_saln_asset_personal_properties', function (
            Blueprint $table
        ) {
            $table->id('saln_asset_personal_property_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('description')->nullable();
            $table->string('acquisition_year')->nullable();
            $table->decimal('acquisition_cost', 13)->default(0);

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
        Schema::dropIfExists('tbl_saln_asset_personal_properties');
    }
}
