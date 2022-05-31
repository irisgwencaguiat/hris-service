<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsOtherInfoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pds_other_info_questions', function (Blueprint $table) {
            $table->id('pds_other_info_question_id');
            $table->unsignedBigInteger('employee_id');

            $table->boolean('question_34a')->default(false)->comment('a. within the third degree?');
            $table->boolean('question_34b')->default(false)->comment('b. within the fourth degree (for Local Government Unit - Career Employees)?');
            $table->text('question_34b_details')->nullable()->comment('If YES, give details');

            $table->boolean('question_35a')->default(false)->comment('a. Have you ever been found guilty of any administrative offense?');
            $table->text('question_35a_details')->nullable()->comment('If YES, give details');
            $table->boolean('question_35b')->default(false)->comment('b. Have you been criminally charged before any court?');
            $table->date('question_35b_datefiled')->nullable()->comment('If YES, give details: Date Filed');
            $table->string('question_35b_casestatus')->nullable()->comment('If YES, give details: Status of Case/s');

            $table->boolean('question_36')->default(false)->comment('Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?');
            $table->text('question_36_details')->nullable()->comment('If YES, give details');

            $table->boolean('question_37a')->default(false)->comment('a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?');
            $table->text('question_37a_details')->nullable()->comment('If YES, give details');

            $table->boolean('question_37b')->default(false)->comment('b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?');
            $table->text('question_37b_details')->nullable()->comment('If YES, give details');

            $table->boolean('question_39')->default(false)->comment('Have you acquired the status of an immigrant or permanent resident of another country?');
            $table->text('question_39_details')->nullable()->comment('If YES, give details(country)');

            $table->boolean('question_40a')->default(false)->comment('Are you a member of any indigenous group?');
            $table->text('question_40a_details')->nullable()->comment('If YES, please specify');
            $table->boolean('question_40b')->default(false)->comment('Are you a person with disability?');
            $table->text('question_40b_idno')->nullable()->comment('If YES, please specify ID no');
            $table->boolean('question_40c')->default(false)->comment('Are you a solo parent?');
            $table->text('question_40c_idno')->nullable()->comment('If YES, please specify ID no');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_pds_other_info_questions', [
            ['name' => 'question_34a', 'desc' => 'a. within the third degree?'],
            ['name' => 'question_34b', 'desc' => 'b. within the fourth degree (for Local Government Unit - Career Employees)?'],
            ['name' => 'question_34b_details', 'desc' => 'If YES, give details'],
            ['name' => 'question_35a', 'desc' => 'a. Have you ever been found guilty of any administrative offense?'],
            ['name' => 'question_35a_details', 'desc' => 'If YES, give details'],
            ['name' => 'question_35b', 'desc' => 'b. Have you been criminally charged before any court?'],
            ['name' => 'question_35b_datefiled', 'desc' => 'If YES, give details: Date Filed'],
            ['name' => 'question_35b_casestatus', 'desc' => 'If YES, give details: Status of Case/s'],
            ['name' => 'question_36', 'desc' => 'Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?'],
            ['name' => 'question_36_details', 'desc' => 'If YES, give details'],
            ['name' => 'question_37a', 'desc' => 'a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?'],
            ['name' => 'question_37a_details', 'desc' => 'If YES, give details'],
            ['name' => 'question_37b', 'desc' => 'b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?'],
            ['name' => 'question_37b_details', 'desc' => 'If YES, give details'],
            ['name' => 'question_39', 'desc' => 'Have you acquired the status of an immigrant or permanent resident of another country?'],
            ['name' => 'question_39_details', 'desc' => 'If YES, give details(country)'],
            ['name' => 'question_40a', 'desc' => 'Are you a member of any indigenous group?'],
            ['name' => 'question_40a_details', 'desc' => 'If YES, please specify'],
            ['name' => 'question_40b', 'desc' => 'Are you a person with disability?'],
            ['name' => 'question_40b_idno', 'desc' => 'If YES, please specify ID no'],
            ['name' => 'question_40c', 'desc' => 'Are you a solo parent?'],
            ['name' => 'question_40c_idno', 'desc' => 'If YES, please specify ID no'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pds_other_info_questions');
    }
}
