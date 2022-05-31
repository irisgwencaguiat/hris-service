<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReschemeEvaluationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Eval form categories table
        Schema::create('tbl_evalform_categories', function (Blueprint $table) {
            $table->id('evalform_category_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Referenced Evaluation Form');
            $table->string('desc')->comment('Category Description');
            $table->double('percentage', 5, 2)->comment('Category Equivalent Percentage');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_categories', [
            ['name' => 'eval_form_id', 'desc' => 'Referenced to the Evaluation Form'],
            ['name' => 'desc', 'desc' => 'Category Description'],
            ['name' => 'percentage', 'desc' => 'Category Equivalent Percentage'],
        ]);

        // Create Eval form categories audit
        Schema::create('tbl_evalform_category_audits', function (Blueprint $table) {
            $table->id('evalform_component_category_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evalform_category_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Referenced Evaluation Form');
            $table->string('desc')->comment('Category Description');
            $table->double('percentage', 5, 2)->comment('Category Equivalent Percentage');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_category_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'eval_form_id', 'desc' => 'Form where the component is included'],
            ['name' => 'desc', 'desc' => 'Component Description'],
            ['name' => 'percentage', 'desc' => 'Percentage equivalent of the component'],
        ]);



        // Recreate components table
        Schema::drop('tbl_evalform_components');

        Schema::create('tbl_evalform_components', function (Blueprint $table) {
            $table->id('evalform_component_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Referenced Evaluation form');
            $table->unsignedBigInteger('evalform_category_id')->comment('Referenced Evalform Category');
            $table->unsignedBigInteger('evalform_major_final_output_id')->comment('Major Final Output');
            $table->unsignedBigInteger('evalform_success_indicator_id')->comment('Success Indicator');
            $table->unsignedBigInteger('evalform_actual_accomplishment_id')->nullabe()->comment('Actual Accomplishment');
            $table->boolean('need_comment')->default(false)->comment('if the success indicator already have display text for accomplishments');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_components', [
            ['name' => 'eval_form_id', 'desc' => 'Referenced Evaluation form'],
            ['name' => 'evalform_category_id', 'desc' => 'Referenced Evalform Category'],
            ['name' => 'evalform_major_final_output_id', 'desc' => 'Major Final Output'],
            ['name' => 'evalform_success_indicator_id', 'desc' => 'Success Indicator'],
            ['name' => 'evalform_actual_accomplishment_id', 'desc' => 'Actual Accomplishment'],
            ['name' => 'need_comment', 'desc' => 'if the success indicator already have display text for accomplishments'],
        ]);

        // Recreate components audit
        Schema::drop('tbl_evalform_component_audits');

        Schema::create('tbl_evalform_component_audits', function (Blueprint $table) {
            $table->id('evalform_component_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();
            
            $table->unsignedBigInteger('evalform_component_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Referenced Evaluation form');
            $table->unsignedBigInteger('evalform_category_id')->comment('Referenced Evalform Category');
            $table->unsignedBigInteger('evalform_major_final_output_id')->comment('Major Final Output');
            $table->unsignedBigInteger('evalform_success_indicator_id')->comment('Success Indicator');
            $table->unsignedBigInteger('evalform_actual_accomplishment_id')->nullabe()->comment('Actual Accomplishment');
            $table->boolean('need_comment')->default(false)->comment('if the success indicator already have display text for accomplishments');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_component_audits', [
            ['name' => 'eval_form_id', 'desc' => 'Referenced Evaluation form'],
            ['name' => 'evalform_category_id', 'desc' => 'Referenced Evalform Category'],
            ['name' => 'evalform_major_final_output_id', 'desc' => 'Major Final Output'],
            ['name' => 'evalform_success_indicator_id', 'desc' => 'Success Indicator'],
            ['name' => 'evalform_actual_accomplishment_id', 'desc' => 'Actual Accomplishment'],
            ['name' => 'need_comment', 'desc' => 'if the success indicator already have display text for accomplishments'],
        ]);

        // Revove unnecessary columns and audits in table major final outputs
        Schema::table('tbl_evalform_major_final_outputs', function (Blueprint $table) {
            $table->dropColumn('evalform_component_id');
        });
        Schema::drop('tbl_evalform_major_final_output_audits');

        // Revove unnecessary columns and audits in table success indicators
        Schema::table('tbl_evalform_success_indicators', function (Blueprint $table) {
            $table->dropColumn('evalform_major_final_output_id');
            $table->dropColumn('has_actual_accomplishment_displaytext');
            $table->dropColumn('actual_accomplishment_displaytext');
            $table->dropColumn('need_comment');
        });
        Schema::drop('tbl_evalform_success_indicator_audits');

        // Create actual accomplishment table
        Schema::create('tbl_evalform_actual_accomplishments', function (Blueprint $table) {
            $table->id('evalform_major_actual_accomplishment_id');
            $table->text('desc')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        // Add category and component id in answer
        Schema::table('tbl_evaluation_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('evalform_category_id')->after('evaluation_id')->comment('Referenced to Evalform category');
            $table->unsignedBigInteger('evalform_component_id')->after('evalform_category_id')->comment('Referenced to Evalform component');
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answers', [
            ['name' => 'evalform_category_id', 'desc' => 'Referenced to Evalform category'],
            ['name' => 'evalform_component_id', 'desc' => 'Referenced to Evalform component'],
        ]);

        // Remove evaluation answer component
        Schema::drop('tbl_evaluation_answer_components');
        Schema::drop('tbl_evaluation_answer_component_audits');


        // Create evaluation answer categories
        Schema::create('tbl_evaluation_answer_categories', function (Blueprint $table) {
            $table->id('evaluation_answer_category_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Reference to evaluations');
            $table->unsignedBigInteger('evalform_category_id')->comment('Reference to eval form categories');
            $table->unsignedDouble('subtotal')->default(0)->comment('Average of scores per category');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answer_categories', [
            ['name' => 'evaluation_id', 'desc' => 'Reference to evaluations'],
            ['name' => 'evalform_category_id', 'desc' => 'Reference to eval form categories'],
            ['name' => 'subtotal', 'desc' => 'Average of scores per category'],
        ]);

        // Audit
        Schema::create('tbl_evaluation_answer_category_audits', function (Blueprint $table) {
            $table->id('evaluation_answer_category_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('evaluation_answer_category_id');
            $table->unsignedBigInteger('evaluation_id')->comment('Reference to evaluations');
            $table->unsignedBigInteger('evalform_category_id')->comment('Reference to eval form categories');
            $table->unsignedDouble('subtotal')->default(0)->comment('Average of scores per category');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evaluation_answer_category_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'evaluation_id', 'desc' => 'Reference to evaluations'],
            ['name' => 'evalform_category_id', 'desc' => 'Reference to eval form categories'],
            ['name' => 'subtotal', 'desc' => 'Average of scores per category'],
        ]);
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
