<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsFamilybackgroundChildAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_familybackground_child_audits', function (Blueprint $table) {
            $table->id('pds_familybackground_child_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pds_familybackground_child_id');
            $table->unsignedBigInteger('employee_id');

            $table->string('child_last_name')->nullable();
            $table->string('child_middle_name')->nullable();
            $table->string('child_first_name')->nullable();
            $table->string('child_suffix')->nullable();
            $table->date('date_of_birth')->nullable();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_familybackground_child_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_familybackground_child_audits');
    }
}
