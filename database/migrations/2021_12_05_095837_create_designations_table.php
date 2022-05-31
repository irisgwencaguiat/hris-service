<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_designations', function (Blueprint $table) {
            $table->id('designation_id');
            $table->string('name');
            $table->string('description');
            $table->bigInteger('level');
            $table->timestamps();
        });

        DB::table('tbl_designations')->insert([
            [
                'name' => 'Administrator',
                'description' => 'Highest designation in the system for developers',
                'level' => 1
            ],
            [
                'name' => 'Employee',
                'description' => 'Standard designation for the employee/non-admin personnel',
                'level' => 2
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_designations');
    }
}
