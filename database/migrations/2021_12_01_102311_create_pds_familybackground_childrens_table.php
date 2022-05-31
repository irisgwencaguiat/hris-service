<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsFamilybackgroundChildrensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_familybackground_children', function (Blueprint $table) {
            $table->id('pds_familybackground_child_id');
            $table->unsignedBigInteger('employee_id');

            $table->string('child_last_name')->nullable();
            $table->string('child_middle_name')->nullable();
            $table->string('child_first_name')->nullable();
            $table->string('child_suffix')->nullable();
            $table->date('date_of_birth')->nullable();
            
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
        Schema::dropIfExists('tbl_pds_familybackground_children');
    }
}
