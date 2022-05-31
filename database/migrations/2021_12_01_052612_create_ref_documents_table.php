<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->string('document_name')->unique();
            $table->unsignedBigInteger('file_id')->nullable()->comment('Folder where the document is stored/included.');
            $table->string('form_no', 50)->nullable()->comment('CS Form No. displayed at the top of the form.');
            $table->string('revised_no', 50)->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'ref_documents', [
            ['name' => 'file_id', 'desc' => 'Folder where the document is stored/included.'],
            ['name' => 'form_no', 'desc' => 'CS Form No. displayed at the top of the form.'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_documents');
    }
}
