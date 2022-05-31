<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_ratings', function (Blueprint $table) {
            $table->id('rating_id');
            $table->double('upper_bound', 5, 2)->default(0)->comment('Upper boundary of the range');
            $table->double('lower_bound', 5, 2)->default(0)->comment('Lower boundary of the range');
            $table->string('rating')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Add description for sqlsrv
        sqlsrvAddTableColumDescs('dbo', 'ref_ratings', [
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
        Schema::dropIfExists('ref_ratings');
    }
}
