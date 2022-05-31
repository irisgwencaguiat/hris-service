<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalnBusinessInterestFinancialConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tbl_saln_business_interest_financial_connections',
            function (Blueprint $table) {
                $table->id('saln_business_interest_financial_connection_id');
                $table->unsignedBigInteger('employee_id');
                $table->string('entity_business_enterprise_name')->nullable();
                $table->string('business_address')->nullable();
                $table
                    ->string('business_interest_financial_connection_nature')
                    ->nullable();
                $table
                    ->string('acquisition_of_interest_connection_date')
                    ->nullable();

                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(
            'tbl_saln_business_interest_financial_connections'
        );
    }
}
