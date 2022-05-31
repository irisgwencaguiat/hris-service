<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeUsedLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_used_leaves', function (Blueprint $table) {
            $table->id('employee_used_leave_id');
            $table->unsignedBigInteger('employee_leave_id')->comment('Leave where the hours to be subtracted');

            $table->unsignedBigInteger('employee_id')->comment('Employee');
            $table->unsignedBigInteger('leave_type')->nullable()->comment('Leave Type');
            $table->double('leave', 8, 2)->default(0)->comment('Leave hours');
            $table->boolean('paid')->default(false)->comment('if the leave is paid or without paid');
            $table->timestamp('leave_at')->useCurrent();
            $table->text('reason')->nullable()->comment('Reason for the leave');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_used_leaves', [
            ['name' => 'employee_leave_id', 'desc' => 'Leave where the hours to be subtracted'],
            ['name' => 'employee_id', 'desc' => 'Employee'],
            ['name' => 'leave_type', 'desc' => 'Leave Type'],
            ['name' => 'leave', 'desc' => 'Leave hours'],
            ['name' => 'paid', 'desc' => 'if the leave is paid or without paid'],
            ['name' => 'reason', 'desc' => 'Reason for the leave'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_used_leaves');
    }
}
