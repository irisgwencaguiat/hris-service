<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RefRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('01', 'REGION I', '01', 'Ilocos Region');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('02', 'REGION II', '02', 'Cagayan Valley');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('03', 'REGION III', '03', 'Central Luzon');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('04', 'REGION IV-A', '04', 'CALABARZON');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('05', 'REGION V', '05', 'Bicol Region');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('06', 'REGION VI', '06', 'Western Visayas');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('07', 'REGION VII', '07', 'Central Visayas');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('08', 'REGION VIII', '08', 'Eastern Visayas');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('09', 'REGION IX', '09', 'Zamboanga Peninsula');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('10', 'REGION X', '10', 'Northern Mindanao');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('11', 'REGION XI', '11', 'Davao Region');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('12', 'REGION XII', '12', 'Soccsksargen');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('13', 'NCR', '13', 'National Capital Region');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('14', 'CAR', '14', 'Cordillera Administrative Region');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('15', 'BARMM', '15', 'Bangsamoro Autonomous Region in Muslim Mindanao');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('16', 'REGION XIII', '16', 'CARAGA');
            INSERT INTO `ref_regions` (`reg_code`, `reg_name`, `nscb_reg_code`, `nscb_reg_name`) VALUES('17', 'REGION IV-B', '17', 'MIMAROPA');
        
        ");
    }
}
