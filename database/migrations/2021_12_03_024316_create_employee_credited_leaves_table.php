<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCreditedLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_credited_leaves', function (Blueprint $table) {
            $table->id('employee_creditedleave_id');
            $table->unsignedBigInteger('employee_leave_id')->comment('Leave where the hours to be subtracted');

            $table->unsignedBigInteger('employee_id')->comment('Employee');
            $table->unsignedBigInteger('leave_type')->nullable()->comment('Leave Type');
            $table->double('leave', 8, 2)->default(0)->comment('Leave hours');
            $table->boolean('paid')->default(false)->comment('if the leave is paid or without paid');
            $table->timestamp('credited_at')->useCurrent();

            $table->string('particulars')->nullable()->comment('Leave Credits, particulars');
            $table->double('earned', 14, 2)->default(0)->comment('Earned Money');
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_employee_credited_leaves', [
            ['name' => 'employee_leave_id', 'desc' => 'Leave where the hours to be subtracted'],
            ['name' => 'employee_id', 'desc' => 'Employee'],
            ['name' => 'leave_type', 'desc' => 'Leave Type'],
            ['name' => 'leave', 'desc' => 'Leave hours'],
            ['name' => 'paid', 'desc' => 'if the leave is paid or without paid'],
            ['name' => 'particulars', 'desc' => 'Leave Credits, particulars'],
            ['name' => 'earned', 'desc' => 'Earned Money'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_employee_credited_leaves');
    }
}
