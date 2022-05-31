<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdittedPdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_editted_pds', function (Blueprint $table) {
            $table->id('editted_pds_id');
            $table->timestamp('editted_at')->useCurrent()->comment('Date and time when the employee eddited the pds');
            $table->unsignedBigInteger('eddited_by')->comment('Editted by');
    
            $table->boolean('approved')->default(false)->comment('Approved or Disapproved changes');
            $table->text('reason')->nullable()->comment('Approved or Disapproved reason');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('Approved by');
            $table->unsignedBigInteger('disapproved_by')->nullable()->comment('Disapproved by');
            $table->timestamp('approved_at')->nullable()->comment('Approved at date and time');
            $table->timestamp('disapproved_at')->nullable()->comment('Dispproved at date and time');

            $table->unsignedBigInteger('employee_id');

            $table->longText('editted_data')->nullable()->comment('JSON type. Collection of all editted fields and tables');
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_editted_pds', [
            ['name' => 'editted_at', 'desc' => 'Date and time when the employee eddited the pds'],
            ['name' => 'eddited_by', 'desc' => 'Editted by'],
            ['name' => 'approved', 'desc' => 'Approved or Disapproved changes'],
            ['name' => 'reason', 'desc' => 'Approved or Disapproved reason'],
            ['name' => 'approved_by', 'desc' => 'Approved by'],
            ['name' => 'disapproved_by', 'desc' => 'Disapproved by'],
            ['name' => 'approved_at', 'desc' => 'Approved at date and time'],
            ['name' => 'disapproved_at', 'desc' => 'Dispproved at date and time'],
            ['name' => 'editted_data', 'desc' => 'JSON type. Collection of all editted fields and tables'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_editted_pds');
    }
}
