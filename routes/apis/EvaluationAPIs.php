<?php
use App\Http\Controllers\EvaluationScheduleController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\EvalformCategoryController;
use App\Http\Controllers\EvalformComponentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/evaluation-schedules', [EvaluationScheduleController::class, 'index'])->name('evaluation_schedules');
    Route::post('/evaluation-schedules', [EvaluationScheduleController::class, 'store'])->name('evaluation_schedules.store');
    Route::patch('/evaluation-schedules/{eval_sched_id}', [EvaluationScheduleController::class, 'update'])->name('evaluation_schedules.patch');
    Route::delete('/evaluation-schedules/{eval_sched_id}', [EvaluationScheduleController::class, 'delete'])->name('evaluation_schedules.delete');
    Route::get('/evaluation-schedules/eval-indexes', [EvaluationScheduleController::class, 'getEvalIndexes'])->name('evaluation_schedules.getEvalIndexes');
    Route::post('/evaluation-schedules/{eval_sched_id}/activate', [EvaluationScheduleController::class, 'activate'])->name('evaluation_schedules.activate');
    Route::post('/evaluation-schedules/{eval_sched_id}/deactivate', [EvaluationScheduleController::class, 'deactivate'])->name('evaluation_schedules.deactivate');
    Route::get('/evaluation-schedules/{eval_sched_id}', [EvaluationScheduleController::class, 'show'])->name('evaluation_schedules.show');

    Route::get('/evaluation-forms', [EvaluationFormController::class, 'index'])->name('evaluation_forms');
    Route::post('/evaluation-forms', [EvaluationFormController::class, 'store'])->name('evaluation_forms.store');
    Route::patch('/evaluation-forms/{eval_form_id}', [EvaluationFormController::class, 'update'])->name('evaluation_forms.patch');
    Route::delete('/evaluation-forms/{eval_form_id}', [EvaluationFormController::class, 'delete'])->name('evaluation_forms.delete');
    Route::get('/evaluation-forms/{eval_form_id}', [EvaluationFormController::class, 'show'])->name('evaluation_forms.show');

    Route::get('/evaluation-forms/{eval_form_id}/categories', [EvalformCategoryController::class, 'index'])->name('evalform_categories');
    Route::post('/evaluation-forms/{eval_form_id}/categories', [EvalformCategoryController::class, 'store'])->name('evalform_categories.store');
    Route::patch('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}', [EvalformCategoryController::class, 'update'])->name('evalform_categories.patch');
    Route::delete('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}', [EvalformCategoryController::class, 'delete'])->name('evalform_categories.delete');
    Route::get('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}', [EvalformCategoryController::class, 'show'])->name('evalform_categories.show');

    Route::get('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}/components', [EvalformComponentController::class, 'index'])->name('evalform_components');
    Route::post('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}/components', [EvalformComponentController::class, 'store'])->name('evalform_components.store');
    Route::patch('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}/components/{evalform_component_id}', [EvalformComponentController::class, 'update'])->name('evalform_components.patch');
    Route::delete('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}/components/{evalform_component_id}', [EvalformComponentController::class, 'delete'])->name('evalform_components.delete');
    Route::get('/evaluation-forms/{eval_form_id}/categories/{evalform_category_id}/components/{evalform_component_id}', [EvalformComponentController::class, 'show'])->name('evalform_components.show');
});