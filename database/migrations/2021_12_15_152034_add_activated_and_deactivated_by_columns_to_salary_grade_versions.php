<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatedAndDeactivatedByColumnsToSalaryGradeVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_salary_grade_versions', function (Blueprint $table) {
            $table->unsignedBigInteger('activated_by')->nullable()->after('activated');
            $table->unsignedBigInteger('deactivated_by')->nullable()->after('activated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_salary_grade_versions', function (Blueprint $table) {
            //
        });
    }
}
