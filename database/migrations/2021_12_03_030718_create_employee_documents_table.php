<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_documents', function (Blueprint $table) {
            $table->id('employee_document_id');

            $table->unsignedBigInteger('document')->comment('Referenced to Ref Documents');
            $table->text('document_path')->nullable();
            $table->text('note')->nullable();
            $table->boolean('approved')->default(false)->comment('Need approval of HR Staff');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_documents', [
            ['name' => 'document', 'desc' => 'Referenced to Ref Documents'],
            ['name' => 'approved', 'desc' => 'Need approval of HR Staff'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_documents');
    }
}
