<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInPdsOtherInfoQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_pds_other_info_questions', function (Blueprint $table) {
            $table->boolean('question_37')->default(false)->comment('Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?');
            $table->text('question_37_details')->nullable()->comment('If YES, give details');

            $table->renameColumn('question_37a', 'question_38a');
            $table->renameColumn('question_37a_details', 'question_38a_details');

            $table->renameColumn('question_37b', 'question_38b');
            $table->renameColumn('question_37b_details', 'question_38b_details');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_pds_other_info_questions', function (Blueprint $table) {
            $table->dropColumn('question_37');
            $table->dropColumn('question_37_details');

            $table->renameColumn('question_38a', 'question_37a');
            $table->renameColumn('question_38a_details', 'question_37a_details');

            $table->renameColumn('question_38b', 'question_37b');
            $table->renameColumn('question_38b_details', 'question_37b_details');

        });
    }
}
