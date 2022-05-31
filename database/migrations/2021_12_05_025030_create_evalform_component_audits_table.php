<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalformComponentAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_evalform_component_audits', function (Blueprint $table) {
            $table->id('evalform_component_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('eval_form_component_id');
            $table->unsignedBigInteger('eval_form_id')->comment('Form where the component is included');
            $table->text('title')->comment('Component Title');
            $table->double('percentage', 5, 2)->comment('Percentage equivalent of the component');
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'tbl_evalform_component_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'eval_form_id', 'desc' => 'Form where the component is included'],
            ['name' => 'title', 'desc' => 'Component Title'],
            ['name' => 'percentage', 'desc' => 'Percentage equivalent of the component'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_evalform_component_audits');
    }
}
