<?php

namespace Database\Seeders;

use App\Models\RefRequirement;
use Illuminate\Database\Seeder;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RefFileSeeder::class,
            RefDocumentSeeder::class,
            RefSuffixSeeder::class,
            RefSexSeeder::class,
            RefCivilStatusSeeder::class,
            RefBloodTypeSeeder::class,
            RefCitizenshipTypeSeeder::class,
            RefCitizenshipProcessSeeder::class,
            RefCountrySeeder::class,
            RefRegionSeeder::class,
            RefProvinceSeeder::class,
            RefCitySeeder::class,
            RefCityClassificationSeeder::class,
            RefBarangaySeeder::class,
            RefEducationLevelSeeder::class,
            RefEmploymentStatusSeeder::class,
            RefLearningDevelopmentTypeSeeder::class,
            AppointmentNatureSeeder::class,
            RefSalaryPerSeeder::class,
            RefLeaveTypeSeeder::class,
            RefLeaveCommutationtypeSeeder::class,
            SystemValueTypeSeeder::class,
            RefEvaluationTypeSeeder::class,
            RefRatingSeeder::class,

            UserTypeSeeder::class,
            RefUserClassificationSeeder::class,
            RefHolidayTypeSeeder::class,
            RefMonthSeeder::class,
            RefPayrollTypeSeeder::class,
            RefDesignationSeeder::class,
            RefRequirementSeeder::class,

            References\SalaryGradeSeeder::class,
            References\StepIncrementSeeder::class,

            ActualSalaryGradeSeeder::class,

            PositionSeeder::class,

            OfficeAndDepartmentSeeder::class,
            
            References\PageTypeSeeder::class,
            References\PageSeeder::class,

            AdditionalEmploymentSatusSeeder::class,
        ]);
    }
}
