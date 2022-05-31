<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefOffice;
use App\Models\RefDepartment;
use Carbon;
use DB;

class OfficeAndDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // offices
        $offices = array(
            array('office_id' => '1','office_code' => 'CSWDD','office_name' => 'City Social Welfare and Development Department'),
            array('office_id' => '2','office_code' => 'GAD','office_name' => 'Gender and Development Office'),
            array('office_id' => '3','office_code' => 'BPLO','office_name' => 'Business Permits and Licensing Office'),
            array('office_id' => '4','office_code' => 'CHRMDD','office_name' => 'City Human Resource Management and Development Department'),
            array('office_id' => '5','office_code' => 'CAD','office_name' => 'City Accounting Department'),
            array('office_id' => '6','office_code' => 'CAsD','office_name' => 'City Assessment Department'),
            array('office_id' => '7','office_code' => 'CBD','office_name' => 'City Budget Department'),
            array('office_id' => '8','office_code' => 'CRD','office_name' => 'City Civil Registry Department'),
            array('office_id' => '9','office_code' => 'CED','office_name' => 'City Engineering Department'),
            array('office_id' => '10','office_code' => 'CENRO','office_name' => 'City Environmental and Natural Resources Office'),
            array('office_id' => '11','office_code' => 'GSD','office_name' => 'City General Services Department'),
            array('office_id' => '12','office_code' => 'CHD','office_name' => 'City Health Department'),
            array('office_id' => '13','office_code' => 'CLD','office_name' => 'City Legal Department'),
            array('office_id' => '14','office_code' => 'CMADAO','office_name' => 'City of Malabon Anti-Drug Abuse Office'),
            array('office_id' => '15','office_code' => 'CMPI','office_name' => 'City of Malabon Polytechnic Institute'),
            array('office_id' => '16','office_code' => 'CMU','office_name' => 'City of Malabon University'),
            array('office_id' => '17','office_code' => 'CPDD','office_name' => 'City Planning and Development Department'),
            array('office_id' => '18','office_code' => 'CTCAO','office_name' => 'City Tourism and Cultural Affairs Office'),
            array('office_id' => '19','office_code' => 'CTD','office_name' => 'City Treasury Department'),
            array('office_id' => '20','office_code' => 'CUPAO','office_name' => 'Community and Urban Poor Affairs Office'),
            array('office_id' => '21','office_code' => 'CDO','office_name' => 'Cooperative Development Office'),
            array('office_id' => '22','office_code' => 'IASO','office_name' => 'Internal Audit Services Office'),
            array('office_id' => '23','office_code' => 'LSB','office_name' => 'Local School Board'),
            array('office_id' => '24','office_code' => 'MCL','office_name' => 'Malabon City Library'),
            array('office_id' => '25','office_code' => 'MDRRMO','office_name' => 'Malabon Disaster Risk Reduction Management Office'),
            array('office_id' => '26','office_code' => 'MMO','office_name' => 'Market Management Office'),
            array('office_id' => '27','office_code' => 'MCAT','office_name' => 'Mayor\'s Complaint and Action Team'),
            array('office_id' => '28','office_code' => 'OSCA','office_name' => 'Office for Senior Citizen Affairs'),
            array('office_id' => '29','office_code' => 'OCA','office_name' => 'Office of the City Administration'),
            array('office_id' => '30','office_code' => 'OCM','office_name' => 'Office of the City Mayor'),
            array('office_id' => '31','office_code' => 'OCVM','office_name' => 'Office of the City Vice Mayor'),
            array('office_id' => '32','office_code' => 'none','office_name' => 'Office of the Secretary to the Sangguniang Panlungsod'),
            array('office_id' => '33','office_code' => 'PDAO','office_name' => 'Persons with Disabilities Affairs Office'),
            array('office_id' => '34','office_code' => 'PESO','office_name' => 'Public Employment Service Office'),
            array('office_id' => '35','office_code' => 'PIO','office_name' => 'Public Information Office'),
            array('office_id' => '36','office_code' => 'PSTMO','office_name' => 'Public Safety and Traffic Management Office'),
            array('office_id' => '37','office_code' => 'SDO','office_name' => 'Sports Development Office'),
            array('office_id' => '46','office_code' => '','office_name' => 'Coun. AQUINO'),
            array('office_id' => '47','office_code' => '','office_name' => 'Coun. CRUZ'),
            array('office_id' => '48','office_code' => '','office_name' => 'Coun. CUNANAN'),
            array('office_id' => '49','office_code' => '','office_name' => 'Coun. NOEL'),
            array('office_id' => '50','office_code' => '','office_name' => 'Coun. DUMALAOG'),
            array('office_id' => '51','office_code' => '','office_name' => 'Coun. E. ORETA'),
            array('office_id' => '52','office_code' => '','office_name' => 'Coun. GARCIA'),
            array('office_id' => '53','office_code' => '','office_name' => 'Coun. LIM'),
            array('office_id' => '54','office_code' => '','office_name' => 'Coun. MA?ALAC'),
            array('office_id' => '55','office_code' => '','office_name' => 'Coun. NOLASCO'),
            array('office_id' => '56','office_code' => '','office_name' => 'Coun. ONA'),
            array('office_id' => '57','office_code' => '','office_name' => 'Coun. P. ORETA'),
            array('office_id' => '58','office_code' => '','office_name' => 'Coun. VICENCIO'),
            array('office_id' => '59','office_code' => '','office_name' => 'Coun. YAMBAO')
        );

        foreach ($offices as $office) {
            DB::table('ref_offices')->insert($office);
            $o = RefOffice::find($office['office_id']);

            $o->created_at = Carbon\Carbon::now();
            $o->updated_at = Carbon\Carbon::now();
            $o->save();
        } 

        // departments
        $departments = array(
            array('department_id' => '8','department_code' => 'BAC','department_name' => 'Bids and Awards Committee','office_id' => '11'),
            array('department_id' => '9','department_code' => '','department_name' => 'Nutrition Office','office_id' => '12'),
            array('department_id' => '10','department_code' => '','department_name' => 'Health Centers/Super Health Centers','office_id' => '12'),
            array('department_id' => '11','department_code' => 'OsMal','department_name' => 'Ospital ng Malabon','office_id' => '12'),
            array('department_id' => '12','department_code' => 'MISD','department_name' => 'Management Information Systems Division','office_id' => '29'),
            array('department_id' => '13','department_code' => 'CSU','department_name' => 'City Security Unit','office_id' => '36')
        );

        foreach ($departments as $department) {
            DB::table('ref_departments')->insert($department);
            $d = RefDepartment::find($department['department_id']);

            $d->created_at = Carbon\Carbon::now();
            $d->updated_at = Carbon\Carbon::now();
            $d->save();
        } 
    }
}
