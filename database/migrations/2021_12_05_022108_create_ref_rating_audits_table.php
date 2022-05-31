<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefRatingAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_rating_audits', function (Blueprint $table) {
            $table->id('ref_rating_audit_id');
            $table->unsignedBigInteger('audit_by')->nullable();
            $table->timestamp('audit_at')->useCurrent();
            $table->string('audit_operation')->comment('(I) Insert, (U) Update, (D) Delete');
            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('rating_id');
            $table->double('upper_bound', 5, 2)->default(0)->comment('Upper boundary of the range');
            $table->double('lower_bound', 5, 2)->default(0)->comment('Lower boundary of the range');
            $table->string('rating')->nullable();
        });

        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'ref_rating_audits', [
            ['name' => 'audit_operation', 'desc' => '(I) Insert, (U) Update, (D) Delete'],
            ['name' => 'upper_bound', 'desc' => 'Upper boundary of the range'],
            ['name' => 'lower_bound', 'desc' => 'Lower boundary of the range'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_rating_audits');
    }
}
