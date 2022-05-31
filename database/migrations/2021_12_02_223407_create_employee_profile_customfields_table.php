<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProfileCustomfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_profile_customfields', function (Blueprint $table) {
            $table->id('employee_profile_customfield_id');
            $table->string('field_name', 100)->unique()->comment('Label for custom field');
            $table->text('field_desc')->nullable()->comment('Description or instruction about the customfield');
            $table->unsignedBigInteger('input_type_id')->nullable();
            $table->string('reference_table')->nullable();
            $table->string('reference_column')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_profile_customfields', [
            ['name' => 'field_name', 'desc' => 'Label for custom field'],
            ['name' => 'field_desc', 'desc' => 'Description or instruction about the customfield'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_profile_customfields');
    }
}
