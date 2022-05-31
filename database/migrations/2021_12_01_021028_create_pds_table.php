<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds', function (Blueprint $table) {
            $table->id('pds_id');
            $table->unsignedBigInteger('employee_id')->unique();

            $table->string('cs_id_no')->nullable()->comment('1. CS ID No (Do not fill up. For CSC use only');
            $table->text('photo_path')->nullable();
            $table->text('esignature_path')->nullable();
            $table->text('right_thumbark_path')->nullable();

            $table->string('govt_issued_id')->nullable()->comment('Government Issued ID: (ex. Passport)');
            $table->string('govt_issued_idno')->nullable()->comment('ID/License/Passport No.');
            $table->string('govt_issued_at')->nullable()->comment('Date/Place of Issuance');
            $table->date('date_accomplished')->useCurrent();
            $table->text('person_administering_oath')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds', [
            ['name' => 'cs_id_no', 'desc' => '1. CS ID No (Do not fill up. For CSC use only'],
            ['name' => 'govt_issued_id', 'desc' => 'Government Issued ID: (ex. Passport)'],
            ['name' => 'govt_issued_idno', 'desc' => 'ID/License/Passport No.'],
            ['name' => 'govt_issued_at', 'desc' => 'Date/Place of Issuance'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds');
    }
}
