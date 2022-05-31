<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsLearningDevelopmentAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_learning_development_audits', function (Blueprint $table) {
            $table->id('pds_learning_development_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('pds_learning_development_id');
            $table->unsignedBigInteger('employee_id');
            $table->text('title')->comment('Title of Learning and Development Interventins/Training Programs');
            $table->date('date_from')->nullable()->comment('Inclusive Date of Attendance From');
            $table->date('date_to')->nullable()->comment('Inclusive Date of Attendance To');
            $table->double('no_of_hours')->default(0)->comment('Number of Hours');
            $table->string('learning_development_type')->nullable()->comment('Type of Learning Development');
            $table->string('conducted_by')->nullable()->comment('Conducted/Sponsored by');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_learning_development_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'title', 'desc' => 'Title of Learning and Development Interventins/Training Programs'],
            ['name' => 'date_from', 'desc' => 'Inclusive Date of Attendance From'],
            ['name' => 'date_to', 'desc' => 'Inclusive Date of Attendance To'],
            ['name' => 'no_of_hours', 'desc' => 'Number of Hours'],
            ['name' => 'learning_development_type', 'desc' => 'Type of Learning Development'],
            ['name' => 'conducted_by', 'desc' => 'Conducted/Sponsored by'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_learning_development_audits');
    }
}
