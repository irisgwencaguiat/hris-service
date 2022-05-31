<?php
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\payroll\HolidayTaggingController;
use App\Http\Controllers\payroll\PayrollController;
use App\Http\Controllers\payroll\WorkDayController;
use App\Http\Controllers\PhilhealthRateController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TrainLawController;
use App\Http\Controllers\TrainLawTaxController;
use App\Http\Controllers\TrainLawAffectedController;
use App\Http\Controllers\EmployeeLoanRuleController;
use App\Http\Controllers\EmployeeLoanController;
use App\Http\Controllers\EmployeeNoPayLoanController;
use App\Http\Controllers\EmployeeMonthlySalaryController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/payrolls', [PayrollController::class, 'store']);
    Route::get('/payrolls', [PayrollController::class, 'index']);
    Route::get('/payrolls/{id}', [PayrollController::class, 'show']);
    Route::put('/payrolls/{id}', [PayrollController::class, 'update']);
    Route::delete('/payrolls/{id}', [PayrollController::class, 'destroy']);

    Route::post('/work-days', [WorkDayController::class, 'store']);
    Route::get('/work-days/{year}', [WorkDayController::class, 'show']);
    Route::put('/work-days/{id}', [WorkDayController::class, 'update']);
    Route::delete('/work-days/{id}', [WorkDayController::class, 'destroy']);

    Route::post('/holidays', [HolidayTaggingController::class, 'store']);
    Route::get('/holidays/{year}', [HolidayTaggingController::class, 'show']);
    Route::put('/holidays/{id}', [HolidayTaggingController::class, 'update']);
    Route::delete('/holidays/{id}', [
        HolidayTaggingController::class,
        'destroy',
    ]);

    Route::resource('/benefits', BenefitController::class);
    Route::post('/benefits/{id}/activate', [
        BenefitController::class,
        'activate',
    ]);
    Route::post('/benefits/{id}/deactivate', [
        BenefitController::class,
        'deactivate',
    ]);
    Route::get('/benefits/actives/get', [
        BenefitController::class,
        'getActives',
    ]);

    Route::resource('/philhealth-rates', PhilhealthRateController::class);
    Route::post('/philhealth-rates/{id}/activate', [
        PhilhealthRateController::class,
        'activate',
    ]);
    Route::post('/philhealth-rates/{id}/deactivate', [
        PhilhealthRateController::class,
        'deactivate',
    ]);
    Route::get('/philhealth-rates/active/get', [
        PhilhealthRateController::class,
        'getActive',
    ]);

    Route::resource('/taxes', TaxController::class);
    Route::post('/taxes/{id}/activate', [TaxController::class, 'activate']);
    Route::post('/taxes/{id}/deactivate', [TaxController::class, 'deactivate']);
    Route::get('/taxes/actives/get', [TaxController::class, 'getActives']);

    Route::resource('/train-laws', TrainLawController::class);
    Route::post('/train-laws/{id}/activate', [
        TrainLawController::class,
        'activate',
    ]);
    Route::post('/train-laws/{id}/deactivate', [
        TrainLawController::class,
        'deactivate',
    ]);
    Route::get('/train-laws/active/get', [
        TrainLawController::class,
        'getActive',
    ]);

    Route::resource('/train-law-taxes', TrainLawTaxController::class);

    Route::resource('/train-law-affecteds', TrainLawAffectedController::class);

    Route::resource('/employee-loan-rules', EmployeeLoanRuleController::class);
    Route::post('/employee-loan-rules/{id}/activate', [
        EmployeeLoanRuleController::class,
        'activate',
    ]);
    Route::post('/employee-loan-rules/{id}/deactivate', [
        EmployeeLoanRuleController::class,
        'deactivate',
    ]);
    Route::get('/employee-loan-rules/active/get', [
        EmployeeLoanRuleController::class,
        'getActive',
    ]);

    Route::resource('/employee-loans', EmployeeLoanController::class);

    Route::resource(
        '/employee-no-pay-loans',
        EmployeeNoPayLoanController::class
    );

    Route::get('/employee-monthly-salaries', [
        EmployeeMonthlySalaryController::class,
        'index',
    ]);
});
