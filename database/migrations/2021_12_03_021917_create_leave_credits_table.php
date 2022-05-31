<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_leave_credits', function (Blueprint $table) {
            $table->id('leave_credit_id');

            $table->unsignedBigInteger('employee_id')->comment('Employee');
            $table->double('total_leaves', 8, 2)->default(0)->comment('Number of leaves');
            $table->double('total_vacation_leaves', 8, 2)->default(0)->comment('Total vacation leaves');
            $table->double('total_sick_leaves', 8, 2)->default(0)->comment('Total sick leaves');
            $table->double('amount', 14, 2)->default(0);

            $table->unsignedBigInteger('department')->nullable()->comment('Deparment for Permanent Plantilla');
            $table->unsignedBigInteger('project')->nullable()->comment('Project for Project Plantilla');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Position');

            $table->date('appointed_date')->nullable()->comment('Appointed Date');
            $table->string('bond_no')->nullable()->comment('Bond No.');

            $table->timestamp('creditted_at')->useCurrent();
            $table->unsignedBigInteger('cty_hrm_officer')->nullable()->comment('City HRM Officer');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_leave_credits', [
            ['name' => 'employee_id', 'desc' => 'Employee'],
            ['name' => 'total_leaves', 'desc' => 'Number of leaves'],
            ['name' => 'total_vacation_leaves', 'desc' => 'Total vacation leaves'],
            ['name' => 'total_sick_leaves', 'desc' => 'Total sick leaves'],
            ['name' => 'department', 'desc' => 'Deparment for Permanent Plantilla'],
            ['name' => 'project', 'desc' => 'Project for Project Plantilla'],
            ['name' => 'position_id', 'desc' => 'Position'],
            ['name' => 'appointed_date', 'desc' => 'Appointed Date'],
            ['name' => 'bond_no', 'desc' => 'Bond No.'],
            ['name' => 'cty_hrm_officer', 'desc' => 'City HRM Officer'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_leave_credits');
    }
}
