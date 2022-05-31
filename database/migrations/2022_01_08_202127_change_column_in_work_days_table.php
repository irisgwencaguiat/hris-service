<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ChangeColumnInWorkDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('tbl_work_days', function (Blueprint $table) {
            $table->renameColumn('id', 'work_day_id');
            $table
                ->double('work_days')
                ->nullable()
                ->change();
            $table
                ->double('edit_no')
                ->default(0)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('tbl_work_days', function (Blueprint $table) {
            $table->renameColumn('work_day_id', 'id');
            $table
                ->string('work_days')
                ->nullable()
                ->change();
            $table
                ->double('edit_no')
                ->nullable()
                ->change();
        });
    }
}
