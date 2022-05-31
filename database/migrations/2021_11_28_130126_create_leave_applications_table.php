<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_leave_applications', function (Blueprint $table) {
            $table->id('leave_application_id');

            $table->unsignedBigInteger('employee_id')->comment('Employee');
            
            $table->unsignedBigInteger('department')->nullable()->comment('Deparment for Permanent Plantilla');
            $table->unsignedBigInteger('project')->nullable()->comment('Project for Project Plantilla');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Position');

            $table->timestamp('date_filed')->comment('Date of Filling');
            $table->double('salary_per_month', 14, 2)->default(0);
        
            $table->unsignedBigInteger('leave_type')->comment('Type of Leave');
            $table->string('vacation_reason')->nullable()->comment('To Seek Employment, or specify');
            $table->string('spent_at')->nullable()->comment('Vacation - Within in the PH, Abroad. Sick Leave - In Hospital, Out Patient');
            $table->timestamp('date_from')->useCurrent()->comment('Inclusive Date from');
            $table->timestamp('date_to')->useCurrent()->comment('Inclusive Date to');
            $table->unsignedBigInteger('leave_commutation_type')->comment('Requested or Not Requested');
            $table->text('esignature_path')->nullable();
            $table->boolean('approved')->default(false)->comment('Is Approved?');
            $table->longText('disapproval_reason')->nullable();
            $table->date('certification_of_leave_creds')->useCurrent();
            $table->double('total_days', 8 ,2)->comment('Total days in Certification of Leave Credits');

            $table->double('paid_days')->default(0);
            $table->double('unpaid_days')->default(0);
            $table->longText('other_days')->nullable();

            $table->unsignedBigInteger('city_hrmd_officer');
            $table->longText('disapproved_reason')->nullable()->comment('Disapproved Reason');
            $table->unsignedBigInteger('authorized_official')->nullable()->comment('Authorized Official');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_leave_applications', [
            ['name' => 'employee_id', 'desc' => 'Employee'],
            ['name' => 'department', 'desc' => 'Deparment for Permanent Plantilla'],
            ['name' => 'project', 'desc' => 'Project for Project Plantilla'],
            ['name' => 'position_id', 'desc' => 'Position'],
            ['name' => 'date_filed', 'desc' => 'Date of Filling'],
            ['name' => 'leave_type', 'desc' => 'Type of Leave'],
            ['name' => 'vacation_reason', 'desc' => 'To Seek Employment, or specify'],
            ['name' => 'spent_at', 'desc' => 'Vacation - Within in the PH, Abroad. Sick Leave - In Hospital, Out Patient'],
            ['name' => 'date_from', 'desc' => 'Inclusive Date from'],
            ['name' => 'date_to', 'desc' => 'Inclusive Date to'],
            ['name' => 'leave_commutation_type', 'desc' => 'Requested or Not Requested'],
            ['name' => 'approved', 'desc' => 'Is Approved?'],
            ['name' => 'total_days', 'desc' => 'Total days in Certification of Leave Credits'],
            ['name' => 'disapproved_reason', 'desc' => 'Disapproved Reason'],
            ['name' => 'authorized_official', 'desc' => 'Authorized Official'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_leave_applications');
    }
}
