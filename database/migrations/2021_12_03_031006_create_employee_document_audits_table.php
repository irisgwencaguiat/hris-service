<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDocumentAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_document_audits', function (Blueprint $table) {
            $table->id('employee_document_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();
            $table->unsignedBigInteger('employee_document_id');

            $table->unsignedBigInteger('document')->comment('Referenced to Ref Documents');
            $table->text('document_path')->nullable();
            $table->text('note')->nullable();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_document_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'document', 'desc' => 'Referenced to Ref Documents'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_document_audits');
    }
}
