<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_leaves', function (Blueprint $table) {
            $table->id('employee_leave_id');
            
            $table->unsignedBigInteger('employee_id')->comment('Employee');
            $table->unsignedBigInteger('leave_type')->nullable()->comment('Leave Type');
            $table->double('total_leaves', 8, 2)->default(0)->comment('Total Leave hours');
            $table->double('available_leaves', 8, 2)->default(0)->comment('Available Leave hours');
            $table->unsignedInteger('leaves_for_the_year')->nullable()->comment('Leaves for the year, XXXX');
            $table->date('period_date_from')->nullable()->comment('Starting period of leave effectivity');
            $table->date('period_date_to')->nullable()->comment('Ending period of leave effectivity');
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_leaves', [
            ['name' => 'employee_id', 'desc' => 'Employee'],
            ['name' => 'leave_type', 'desc' => 'Leave Type'],
            ['name' => 'total_leaves', 'desc' => 'Total Leave hours'],
            ['name' => 'available_leaves', 'desc' => 'Available Leave hours'],
            ['name' => 'leaves_for_the_year', 'desc' => 'Leaves for the year, XXXX'],
            ['name' => 'period_date_from', 'desc' => 'Starting period of leave effectivity'],
            ['name' => 'period_date_to', 'desc' => 'Ending period of leave effectivity'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_leaves');
    }
}
