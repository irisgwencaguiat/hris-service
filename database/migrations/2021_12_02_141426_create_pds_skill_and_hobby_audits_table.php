<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsSkillAndHobbyAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_skill_and_hobby_audits', function (Blueprint $table) {
            $table->id('pds_skill_and_hobby_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pds_skills_and_hobby_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('skill')->comment('Special Skill or Hobby');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_skill_and_hobby_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
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
        Schema::dropIfExists('tbl_pds_skill_and_hobby_audits');
    }
}
