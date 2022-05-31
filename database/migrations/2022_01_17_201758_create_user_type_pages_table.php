<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_type_pages', function (Blueprint $table) {
            $table->id('user_type_page_id');
            $table->unsignedBigInteger('user_type_id');
            $table->unsignedBigInteger('user_classification_id')->nullable();
            $table->unsignedBigInteger('page_id');

            $table->unsignedInteger('order_no')->nullable();
            $table->string('route_name')->nullable();
            $table->string('page_icon')->nullable();

            $table->unsignedBigInteger('parent_user_type_page_id')->nullable();
            $table->boolean('has_sub_pages')->default(false);

            $table->boolean('is_activated')->default(true);
            $table->unsignedBigInteger('activated_by')->nullable();
            $table->timestamp('activated_at')->useCurrent();
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_type_pages');
    }
}
