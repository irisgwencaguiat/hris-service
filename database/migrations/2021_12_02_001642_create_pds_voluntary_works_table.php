<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsVoluntaryWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_voluntary_works', function (Blueprint $table) {
            $table->id('pds_voluntary_work_id');
            $table->unsignedBigInteger('employee_id');
            $table->text('organization')->comment('Name & Address of Organization');
            $table->date('date_from')->nullable()->comment('Inclusive Date From');
            $table->date('date_to')->nullable()->comment('Inclusive Date To');
            $table->double('no_of_hours', 8, 2)->default(0)->comment('Number of Hours');
            $table->string('position')->nullable()->comment('Position/Nature of Work');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_voluntary_works', [
            ['name' => 'organization', 'desc' => 'Name & Address of Organization'],
            ['name' => 'date_from', 'desc' => 'Inclusive Date From'],
            ['name' => 'date_to', 'desc' => 'Inclusive Date To'],
            ['name' => 'no_of_hours', 'desc' => 'Number of Hours'],
            ['name' => 'position', 'desc' => 'Position/Nature of Work'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_voluntary_works');
    }
}
