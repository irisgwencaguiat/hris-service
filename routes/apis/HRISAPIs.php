<?php
//use App\Http\Controllers\API\DepartmentsController;
//use App\Http\Controllers\API\PlantillaController;
//use App\Http\Controllers\API\ProfileController;
//use App\Http\Controllers\API\ProjectsController;
//use App\Http\Controllers\API\SalaryGradeController;
//use App\Http\Controllers\API\StepsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePreviousPlantillaController;
use App\Http\Controllers\EmployeePreviousPositionController;
use App\Http\Controllers\EmployeeRequirementController;
use App\Http\Controllers\EmployeeWorkingHourController;
use App\Http\Controllers\DtrController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\PayrollAccountInfoController;
use App\Http\Controllers\pds\AssociationMembershipController;
use App\Http\Controllers\pds\CivilServiceEligibilityController;
use App\Http\Controllers\pds\EducationalBackgroundController;
use App\Http\Controllers\pds\FamilyBackgroundChildrenController;
use App\Http\Controllers\pds\FamilyBackgroundController;
use App\Http\Controllers\pds\GovernmentIdentificationController;
use App\Http\Controllers\pds\LearningDevelopmentController;
use App\Http\Controllers\pds\NonAcademicDistinctionController;
use App\Http\Controllers\pds\OtherInfoQuestionController;
use App\Http\Controllers\pds\PersonalInformationController;
use App\Http\Controllers\pds\ReferenceController;
use App\Http\Controllers\pds\SkillsAndHobbyController;
use App\Http\Controllers\pds\VoluntaryWorkController;
use App\Http\Controllers\pds\WorkExperienceController;
use App\Http\Controllers\SalaryGradeVersionController;
use App\Http\Controllers\ActualSalaryGradeController;
use App\Http\Controllers\saln\AssetPersonalPropertyController;
use App\Http\Controllers\saln\AssetRealPropertyController;
use App\Http\Controllers\saln\BusinessInterestFinancialConnectionController;
use App\Http\Controllers\saln\ChildController;
use App\Http\Controllers\saln\GovernmentServiceRelativeController;
use App\Http\Controllers\saln\LiabilityController;
use App\Http\Controllers\saln\SalnController;
use App\Http\Controllers\saln\VersionController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('/refresh', [AuthController::class, 'refresh']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::get('/employee/{id}', [
        EmployeeController::class,
        'showViaEmployeeNumber',
    ]);
    Route::get('/employees/working-hours/{id}', [
        EmployeeWorkingHourController::class,
        'show',
    ]);
    Route::get('/employees/appointment/{id}', [
        EmployeeController::class,
        'showEmployeeAppointment',
    ]);
    Route::get('/employees/appointment/history/{id}', [
        EmployeeController::class,
        'showEmployeeAppointmentHistory',
    ]);
    Route::get('/employees/report/{id}', [
        EmployeeController::class,
        'showEmployeeReport',
    ]);
    Route::get('/employees/previous-position/{id}', [
        EmployeePreviousPositionController::class,
        'show',
    ]);
    Route::get('/employees/previous-plantilla/{id}', [
        EmployeePreviousPlantillaController::class,
        'show',
    ]);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::put('/employees/working-hours/{id}', [
        EmployeeWorkingHourController::class,
        'update',
    ]);
    Route::put('/employees/appointment/{id}', [
        EmployeeController::class,
        'updateEmployeeAppointment',
    ]);
    Route::put('/employees/report/{id}', [
        EmployeeController::class,
        'updateEmployeeReport',
    ]);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

    Route::get('/employees/generate/ids', [
        EmployeeController::class,
        'generateIDsWithDomPDF',
    ]);

    Route::post('/employees/requirements/{id}', [
        EmployeeRequirementController::class,
        'store',
    ]);
    Route::get('/employees/requirements/{id}', [
        EmployeeRequirementController::class,
        'show',
    ]);
    Route::put('/employees/requirements/{id}', [
        EmployeeRequirementController::class,
        'update',
    ]);
    Route::delete('/employees/requirements/{id}', [
        EmployeeRequirementController::class,
        'destroy',
    ]);

    Route::post('/kiosks', [KioskController::class, 'store']);
    Route::get('/kiosks', [KioskController::class, 'index']);
    Route::get('/kiosks/{id}', [KioskController::class, 'show']);
    Route::put('/kiosks/{id}', [KioskController::class, 'update']);
    Route::delete('kiosks/{id}', [KioskController::class, 'destroy']);
    Route::post('kiosk/passcode', [KioskController::class, 'loginViaPasscode']);

    Route::get('/payroll-account-info/{id}', [
        PayrollAccountInfoController::class,
        'show',
    ]);
    Route::put('/payroll-account-info/{id}', [
        PayrollAccountInfoController::class,
        'update',
    ]);

    /** DTR */
    Route::post('/dtr', [DtrController::class, 'store']);
    /** Admin Add DTR */
    Route::post('/dtr/admin', [DtrController::class, 'adminStoreDtr']);
    /** Admin Update DTR */
    Route::patch('dtr/admin/update/{id}', [DtrController::class, 'update']);
    /** Show  DTR in table */
    Route::get('/dtr/table', [DtrController::class, 'showDtrTable']);
    /** Show employee dtr */
    Route::get('/dtr/{id}', [DtrController::class, 'show']);
    /** Delete DTR */
    Route::delete('/dtr/{id}', [DtrController::class, 'delete']);

    Route::post('/dtr/generate', [DtrController::class, 'generateDtr']);

    Route::prefix('saln')->group(function () {
        Route::post('/versions', [VersionController::class, 'store']);
        Route::get('/versions', [VersionController::class, 'index']);
        Route::get('/versions/actives', [
            VersionController::class,
            'showActive',
        ]);
        Route::get('/versions/{id}', [VersionController::class, 'show']);
        Route::put('/versions/{id}', [VersionController::class, 'update']);
        Route::delete('/versions/{id}', [VersionController::class, 'destroy']);

        Route::post('/assets/real-properties/{id}', [
            AssetRealPropertyController::class,
            'store',
        ]);
        Route::get('/assets/real-properties/{id}', [
            AssetRealPropertyController::class,
            'show',
        ]);
        Route::put('/assets/real-properties/{id}', [
            AssetRealPropertyController::class,
            'update',
        ]);
        Route::delete('/assets/real-properties/{id}', [
            AssetRealPropertyController::class,
            'destroy',
        ]);

        Route::post('/assets/personal-properties/{id}', [
            AssetPersonalPropertyController::class,
            'store',
        ]);
        Route::get('/assets/personal-properties/{id}', [
            AssetPersonalPropertyController::class,
            'show',
        ]);
        Route::put('/assets/personal-properties/{id}', [
            AssetPersonalPropertyController::class,
            'update',
        ]);
        Route::delete('/assets/personal-properties/{id}', [
            AssetPersonalPropertyController::class,
            'destroy',
        ]);

        Route::post('/liabilities/{id}', [LiabilityController::class, 'store']);
        Route::get('/liabilities/{id}', [LiabilityController::class, 'show']);
        Route::put('/liabilities/{id}', [LiabilityController::class, 'update']);
        Route::delete('/liabilities/{id}', [
            LiabilityController::class,
            'destroy',
        ]);

        Route::post('/children/{id}', [ChildController::class, 'store']);
        Route::get('/children/{id}', [ChildController::class, 'show']);
        Route::put('/children/{id}', [ChildController::class, 'update']);
        Route::delete('/children/{id}', [ChildController::class, 'destroy']);

        Route::post('/business-interests/{id}', [
            BusinessInterestFinancialConnectionController::class,
            'store',
        ]);
        Route::get('/business-interests/{id}', [
            BusinessInterestFinancialConnectionController::class,
            'show',
        ]);
        Route::put('/business-interests/{id}', [
            BusinessInterestFinancialConnectionController::class,
            'update',
        ]);
        Route::delete('/business-interests/{id}', [
            BusinessInterestFinancialConnectionController::class,
            'destroy',
        ]);

        Route::post('/government-service-relatives/{id}', [
            GovernmentServiceRelativeController::class,
            'store',
        ]);
        Route::get('/government-service-relatives/{id}', [
            GovernmentServiceRelativeController::class,
            'show',
        ]);
        Route::put('/government-service-relatives/{id}', [
            GovernmentServiceRelativeController::class,
            'update',
        ]);
        Route::delete('/government-service-relatives/{id}', [
            GovernmentServiceRelativeController::class,
            'destroy',
        ]);

        Route::post('/{id}', [SalnController::class, 'store']);
        Route::get('/employees/{id}', [SalnController::class, 'index']);
        Route::get('/{id}', [SalnController::class, 'show']);
        Route::put('/printed/{id}', [SalnController::class, 'update']);
        Route::delete('/{id}', [SalnController::class, 'destroy']);
    });

    Route::prefix('pds')->group(function () {
        Route::get('/personal-information/{id}', [
            PersonalInformationController::class,
            'show',
        ]);
        Route::put('/personal-information/{id}', [
            PersonalInformationController::class,
            'update',
        ]);

        Route::get('/family-background/{id}', [
            FamilyBackgroundController::class,
            'show',
        ]);
        Route::put('/family-background/{id}', [
            FamilyBackgroundController::class,
            'update',
        ]);
        Route::delete('family-background/{id}', [
            FamilyBackgroundController::class,
            'destroy',
        ]);

        Route::post('/family-background/children/{id}', [
            FamilyBackgroundChildrenController::class,
            'store',
        ]);
        Route::get('/family-background/children/{id}', [
            FamilyBackgroundChildrenController::class,
            'show',
        ]);
        Route::put('/family-background/children/{id}', [
            FamilyBackgroundChildrenController::class,
            'update',
        ]);
        Route::delete('family-background/children/{id}', [
            FamilyBackgroundChildrenController::class,
            'destroy',
        ]);

        Route::post('/educational-background/{id}', [
            EducationalBackgroundController::class,
            'store',
        ]);
        Route::get('/educational-background/{id}', [
            EducationalBackgroundController::class,
            'show',
        ]);
        Route::put('/educational-background/{id}', [
            EducationalBackgroundController::class,
            'update',
        ]);
        Route::delete('/educational-background/{id}', [
            EducationalBackgroundController::class,
            'destroy',
        ]);

        Route::post('/civil-service-eligibility/{id}', [
            CivilServiceEligibilityController::class,
            'store',
        ]);
        Route::get('/civil-service-eligibility/{id}', [
            CivilServiceEligibilityController::class,
            'show',
        ]);
        Route::put('/civil-service-eligibility/{id}', [
            CivilServiceEligibilityController::class,
            'update',
        ]);
        Route::delete('/civil-service-eligibility/{id}', [
            CivilServiceEligibilityController::class,
            'destroy',
        ]);

        Route::post('/work-experiences/{id}', [
            WorkExperienceController::class,
            'store',
        ]);
        Route::get('/work-experiences/{id}', [
            WorkExperienceController::class,
            'show',
        ]);
        Route::put('/work-experiences/{id}', [
            WorkExperienceController::class,
            'update',
        ]);
        Route::delete('/work-experiences/{id}', [
            WorkExperienceController::class,
            'destroy',
        ]);

        Route::post('/voluntary-works/{id}', [
            VoluntaryWorkController::class,
            'store',
        ]);
        Route::get('/voluntary-works/{id}', [
            VoluntaryWorkController::class,
            'show',
        ]);
        Route::put('/voluntary-works/{id}', [
            VoluntaryWorkController::class,
            'update',
        ]);
        Route::delete('/voluntary-works/{id}', [
            VoluntaryWorkController::class,
            'destroy',
        ]);

        Route::post('/learning-development/{id}', [
            LearningDevelopmentController::class,
            'store',
        ]);
        Route::get('/learning-development/{id}', [
            LearningDevelopmentController::class,
            'show',
        ]);
        Route::put('/learning-development/{id}', [
            LearningDevelopmentController::class,
            'update',
        ]);
        Route::delete('/learning-development/{id}', [
            LearningDevelopmentController::class,
            'destroy',
        ]);

        Route::post('/skills-and-hobbies/{id}', [
            SkillsAndHobbyController::class,
            'store',
        ]);
        Route::get('/skills-and-hobbies/{id}', [
            SkillsAndHobbyController::class,
            'show',
        ]);
        Route::put('/skills-and-hobbies/{id}', [
            SkillsAndHobbyController::class,
            'update',
        ]);
        Route::delete('/skills-and-hobbies/{id}', [
            SkillsAndHobbyController::class,
            'destroy',
        ]);

        Route::post('/non-academic-distinctions/{id}', [
            NonAcademicDistinctionController::class,
            'store',
        ]);
        Route::get('/non-academic-distinctions/{id}', [
            NonAcademicDistinctionController::class,
            'show',
        ]);
        Route::put('/non-academic-distinctions/{id}', [
            NonAcademicDistinctionController::class,
            'update',
        ]);
        Route::delete('/non-academic-distinctions/{id}', [
            NonAcademicDistinctionController::class,
            'destroy',
        ]);

        Route::post('/association-memberships/{id}', [
            AssociationMembershipController::class,
            'store',
        ]);
        Route::get('/association-memberships/{id}', [
            AssociationMembershipController::class,
            'show',
        ]);
        Route::put('/association-memberships/{id}', [
            AssociationMembershipController::class,
            'update',
        ]);
        Route::delete('/association-memberships/{id}', [
            AssociationMembershipController::class,
            'destroy',
        ]);

        Route::get('/other-info-questions/{id}', [
            OtherInfoQuestionController::class,
            'show',
        ]);
        Route::put('/other-info-questions/{id}', [
            OtherInfoQuestionController::class,
            'update',
        ]);

        Route::post('/references/{id}', [ReferenceController::class, 'store']);
        Route::get('/references/{id}', [ReferenceController::class, 'show']);
        Route::put('/references/{id}', [ReferenceController::class, 'update']);
        Route::delete('/references/{id}', [
            ReferenceController::class,
            'destroy',
        ]);

        Route::post('/government-ids/{id}', [
            GovernmentIdentificationController::class,
            'store',
        ]);
        Route::get('/government-ids/{id}', [
            GovernmentIdentificationController::class,
            'show',
        ]);
        Route::put('/government-ids/{id}', [
            GovernmentIdentificationController::class,
            'update',
        ]);
        Route::delete('/government-ids/{id}', [
            GovernmentIdentificationController::class,
            'destroy',
        ]);
    });

    // salary grade versions
    Route::prefix('salary-grade-versions')->group(function () {
        Route::get('/', [SalaryGradeVersionController::class, 'index']);
        Route::post('/', [SalaryGradeVersionController::class, 'store']);
        Route::patch('/{id}', [SalaryGradeVersionController::class, 'update']);
        Route::delete('/{id}', [SalaryGradeVersionController::class, 'delete']);
        Route::get('/{id}', [SalaryGradeVersionController::class, 'show']);
        Route::post('/{id}/activate', [
            SalaryGradeVersionController::class,
            'activate',
        ]);
        Route::post('/{id}/deactivate', [
            SalaryGradeVersionController::class,
            'deactivate',
        ]);

        Route::get('/active/get', [
            SalaryGradeVersionController::class,
            'getActive',
        ]);

        // actual salary grades
        Route::prefix('/{id}/salary-grades')->group(function () {
            Route::get('/', [ActualSalaryGradeController::class, 'index']);
            Route::post('/', [ActualSalaryGradeController::class, 'store']);
            Route::patch('/{asgid}', [
                ActualSalaryGradeController::class,
                'update',
            ]);
            Route::delete('/{asgid}', [
                ActualSalaryGradeController::class,
                'delete',
            ]);
            Route::get('/{asgid}', [
                ActualSalaryGradeController::class,
                'show',
            ]);
        });
    });

    //    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    //    Route::post('refresh', [AuthenticationController::class, 'refresh'])->name('refresh');
    //
    //    Route::get('departments', [DepartmentsController::class, 'index'])->name('departments');
    //    Route::get('departments/{id}', [DepartmentsController::class, 'show'])->name('departments.show');
    //    Route::post('departments', [DepartmentsController::class, 'store'])->name('departments.save');
    //    Route::match(['put', 'patch'], 'departments/{id}', [DepartmentsController::class, 'update'])->name('departments.update');
    //    Route::delete('departments/{id}', [DepartmentsController::class, 'destroy'])->name('departments.remove');
    //
    //    Route::get('plantillas', [PlantillaController::class, 'index'])->name('templates');
    //    Route::get('plantillas/{id}', [PlantillaController::class, 'show'])->name('templates.show');
    //    Route::post('plantillas', [PlantillaController::class, 'store'])->name('templates.save');
    //    Route::match(['put', 'patch'], 'plantillas/{id}', [PlantillaController::class, 'update'])->name('templates.update');
    //    Route::delete('plantillas/{id}', [PlantillaController::class, 'destroy'])->name('templates.remove');
    //
    //    Route::get('projects', [ProjectsController::class, 'index'])->name('projects');
    //    Route::get('projects/{id}', [ProjectsController::class, 'show'])->name('projects.show');
    //    Route::post('projects', [ProjectsController::class, 'store'])->name('projects.save');
    //    Route::match(['put', 'patch'], 'projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');
    //    Route::delete('projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.remove');
    //
    //    Route::get('profiles', [ProfileController::class, 'index'])->name('profiles');
    //    Route::get('profiles/{id}', [ProfileController::class, 'show'])->name('profiles.show');
    //    Route::post('profiles', [ProfileController::class, 'store'])->name('profiles.save');
    //    Route::match(['put', 'patch'], 'profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');
    //    Route::delete('profiles/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
    //
    //    Route::get('steps', [StepsController::class, 'index'])->name('steps');
    //    Route::get('steps/{id}', [StepsController::class, 'show'])->name('step.show');
    //
    //    Route::get('salaryGrades', [SalaryGradeController::class, 'index'])->name('salary.grades');
    //    Route::get('salaryGrades/{id}', [SalaryGradeController::class, 'show'])->name('salary.grades.show');

    
    Route::get('/fix/employee-nos', [
        EmployeeController::class,
        'reindexEmployeeNo',
    ]);
});
