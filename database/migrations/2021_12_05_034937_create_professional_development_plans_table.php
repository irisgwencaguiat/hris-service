<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalDevelopmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_professional_development_plans', function (Blueprint $table) {
            $table->id('prof_dev_plan_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Reference where the comments are included');
            $table->unsignedBigInteger('evaluated_by');
            $table->unsignedBigInteger('employee_id')->comment('Person who owns the plan. Evaluatee');
            $table->date('plan_date')->useCurrent()->comment('Planned Date');
            $table->text('aim')->nullable()->comment('Aim of the plan');
            $table->text('objectives')->nullable()->comment('Objectives of the plan');
            $table->date('target_date')->nullable()->comment('Target Date for the development');
            $table->date('evaluation_date')->nullable()->comment('Review Date. Date when the evlauation occured');
            $table->date('achieved_date')->nullable()->comment('Date when the all the objectives met');
            $table->text('comments')->nullable()->comment('Comments in development_purpose_comments of evaluations');
            $table->longText('task')->nullable()->comment('Task');
            $table->longText('outcome')->nullable()->comment('Outcome');
            $table->longText('next_step')->nullable()->comment('Next Step');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Current position of the employee');
            $table->unsignedBigInteger('deparment_id')->nullable()->comment('Current department of the employee');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_professional_development_plans', [
            ['name' => 'evaluation_id', 'desc' => 'Reference where the comments are included'],
            ['name' => 'employee_id', 'desc' => 'Person who owns the plan. Evaluatee'],
            ['name' => 'plan_date', 'desc' => 'Planned Date'],
            ['name' => 'aim', 'desc' => 'Aim of the plan'],
            ['name' => 'objectives', 'desc' => 'Objectives of the plan'],
            ['name' => 'target_date', 'desc' => 'Target Date for the development'],
            ['name' => 'evaluation_date', 'desc' => 'Review Date. Date when the evlauation occured'],
            ['name' => 'achieved_date', 'desc' => 'Date when the all the objectives met'],
            ['name' => 'comments', 'desc' => 'Comments in development_purpose_comments of evaluations'],
            ['name' => 'task', 'desc' => 'Task'],
            ['name' => 'outcome', 'desc' => 'Outcome'],
            ['name' => 'next_step', 'desc' => 'Next Step'],
            ['name' => 'position_id', 'desc' => 'Current position of the employee'],
            ['name' => 'deparment_id', 'desc' => 'Current department of the employee'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_professional_development_plans');
    }
}
