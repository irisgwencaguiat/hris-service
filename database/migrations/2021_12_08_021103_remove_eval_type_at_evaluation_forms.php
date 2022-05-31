<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEvalTypeAtEvaluationForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_evaluation_forms', function (Blueprint $table) {
            $table->dropColumn('eval_type');
        });

        Schema::table('tbl_evaluation_form_audits', function (Blueprint $table) {
            $table->dropColumn('eval_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
