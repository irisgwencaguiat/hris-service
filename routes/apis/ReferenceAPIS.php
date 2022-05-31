<?php

use App\Http\Controllers\reference\CardTypeController;
use App\Http\Controllers\reference\DesignationController;
use App\Http\Controllers\reference\RequirementController;
use App\Http\Controllers\reference\HolidayTypeController;
use App\Http\Controllers\reference\SexController;
use App\Http\Controllers\reference\CivilStatusController;
use App\Http\Controllers\reference\BloodTypeController;
use App\Http\Controllers\reference\CountryController;
use App\Http\Controllers\reference\CitizenTypeController;
use App\Http\Controllers\reference\CitizenProcessController;
use App\Http\Controllers\reference\ProvinceController;
use App\Http\Controllers\reference\CityController;
use App\Http\Controllers\reference\BarangayController;
use App\Http\Controllers\reference\EducationLevelController;
use App\Http\Controllers\reference\AppointmentNature;
use App\Http\Controllers\reference\DocumentController;
use App\Http\Controllers\reference\EmploymentStatusController;
use App\Http\Controllers\reference\EvaluationTypeController;
use App\Http\Controllers\reference\FileController;
use App\Http\Controllers\reference\LearningDevelopmentTypeController;
use App\Http\Controllers\reference\LeaveCommutationTypeController;
use App\Http\Controllers\reference\LeaveTypeController;
use App\Http\Controllers\reference\SuffixController;
use App\Http\Controllers\reference\UserClassificationController;
use App\Http\Controllers\reference\OfficeController;
use App\Http\Controllers\reference\DepartmentController;
use App\Http\Controllers\reference\UnitController;
use App\Http\Controllers\reference\PositionController;
use App\Http\Controllers\reference\PlantillaController;
use App\Http\Controllers\reference\SalaryGradeController;
use App\Http\Controllers\reference\StepIncrementController;
use App\Http\Controllers\reference\LoanTypeController;
use App\Http\Controllers\reference\SignatoryController;



Route::prefix('/reference')->group(function () {
    Route::get('/sexes', [SexController::class, 'index']);
    Route::get('/civil-statuses', [CivilStatusController::class, 'index']);
    Route::get('/blood-types', [BloodTypeController::class, 'index']);
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/citizen-types', [CitizenTypeController::class, 'index']);
    Route::get('/citizen-processes', [
        CitizenProcessController::class,
        'index',
    ]);
    Route::get('/provinces', [ProvinceController::class, 'index']);
    Route::get('/cities', [CityController::class, 'index']);
    Route::get('/barangays', [BarangayController::class, 'index']);
    Route::get('/education-levels', [EducationLevelController::class, 'index']);
    Route::get('/appointment-natures', [AppointmentNature::class, 'index']);
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/employment-statuses', [
        EmploymentStatusController::class,
        'index',
    ]);
    Route::get('/evaluation-types', [EvaluationTypeController::class, 'index']);
    Route::get('/files', [FileController::class, 'index']);
    Route::get('/learning-development-types', [
        LearningDevelopmentTypeController::class,
        'index',
    ]);
    Route::get('/leave-commutation-types', [
        LeaveCommutationTypeController::class,
        'index',
    ]);
    Route::get('/leave-types', [LeaveTypeController::class, 'index']);
    Route::get('/step-increments', [StepIncrementController::class, 'index']);
    Route::get('/suffixes', [SuffixController::class, 'index']);
    Route::get('/user-classifications', [
        UserClassificationController::class,
        'index',
    ]);
    Route::get('/holiday-types', [HolidayTypeController::class, 'index']);
    Route::get('/designations', [DesignationController::class, 'index']);
    Route::get('/card-types', [CardTypeController::class, 'index']);
    Route::get('/requirements', [RequirementController::class, 'index']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::prefix('/reference')->group(function () {
        // offices
        Route::get('/offices/employees', [OfficeController::class, 'exportEmployees']);
        Route::get('/offices', [OfficeController::class, 'index']);
        Route::post('/offices', [OfficeController::class, 'store']);
        Route::patch('/offices/{office_id}', [
            OfficeController::class,
            'update',
        ]);
        Route::delete('/offices/{office_id}', [
            OfficeController::class,
            'delete',
        ]);
        Route::get('/offices/{office_id}', [OfficeController::class, 'show']);

        // departments
        Route::get('/departments', [DepartmentController::class, 'index']);
        Route::post('/departments', [DepartmentController::class, 'store']);
        Route::patch('/departments/{department_id}', [
            DepartmentController::class,
            'update',
        ]);
        Route::delete('/departments/{department_id}', [
            DepartmentController::class,
            'delete',
        ]);
        Route::get('/departments/{department_id}', [
            DepartmentController::class,
            'show',
        ]);

        // units
        Route::get('/units', [UnitController::class, 'index']);
        Route::post('/units', [UnitController::class, 'store']);
        Route::patch('/units/{unit_id}', [UnitController::class, 'update']);
        Route::delete('/units/{unit_id}', [UnitController::class, 'delete']);
        Route::get('/units/{unit_id}', [UnitController::class, 'show']);

        // positions
        Route::get('/positions', [PositionController::class, 'index']);
        Route::post('/positions', [PositionController::class, 'store']);
        Route::patch('/positions/{position_id}', [
            PositionController::class,
            'update',
        ]);
        Route::delete('/positions/{position_id}', [
            PositionController::class,
            'delete',
        ]);
        Route::get('/positions/{position_id}', [
            PositionController::class,
            'show',
        ]);

        // plantillas
        Route::get('/plantillas', [PlantillaController::class, 'index']);
        Route::post('/plantillas', [PlantillaController::class, 'store']);
        Route::patch('/plantillas/{plantilla_id}', [
            PlantillaController::class,
            'update',
        ]);
        Route::delete('/plantillas/{plantilla_id}', [
            PlantillaController::class,
            'delete',
        ]);
        Route::get('/plantillas/{plantilla_id}', [
            PlantillaController::class,
            'show',
        ]);

        // salary grades
        Route::get('/salary-grades', [SalaryGradeController::class, 'index']);

        // step increments
        Route::get('/step-increments', [
            StepIncrementController::class,
            'index',
        ]);

        // loan types
        Route::resource('/loan-types', LoanTypeController::class);

        // signatories
        Route::resource('/signatories', SignatoryController::class);
        Route::post('/signatories/{id}', [
            SignatoryController::class,
            'update',
        ]);
    });
});
