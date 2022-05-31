<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsFamilyBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_family_backgrounds', function (Blueprint $table) {
            $table->id('pds_family_background_id');
            $table->unsignedBigInteger('employee_id')->unique();
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_suffix')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_employer')->nullable()->comment('Employer/Business Name');
            $table->string('spouse_business_address')->nullable()->comment('Business Address');
            $table->string('spouse_telephone_no')->nullable();
            
            $table->string('father_last_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_suffix')->nullable();

            $table->string('mother_maiden_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_suffix')->nullable();
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_family_backgrounds', [
            ['name' => 'spouse_employer', 'desc' => 'Employer/Business Name'],
            ['name' => 'spouse_business_address', 'desc' => 'Business Address'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_family_backgrounds');
    }
}
