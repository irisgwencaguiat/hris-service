<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsSkillsAndHobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_skills_and_hobbies', function (Blueprint $table) {
            $table->id('pds_skills_and_hobby_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('skill')->comment('Special Skill or Hobby');
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_skills_and_hobbies', [
            ['name' => 'skill', 'desc' => 'Special Skill or Hobby'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_skills_and_hobbies');
    }
}
