<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RefCityClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO `ref_city_classifications` (`city_classification_id`, `city_classification_name`) VALUES(1, 'HUC');
            INSERT INTO `ref_city_classifications` (`city_classification_id`, `city_classification_name`) VALUES(2, 'ICC');
            INSERT INTO `ref_city_classifications` (`city_classification_id`, `city_classification_name`) VALUES(3, 'Component');
        ");
    }
}
