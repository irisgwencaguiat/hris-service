<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Illuminate\Database\QueryException;
use App\Models\EvaluationForm;
use App\Models\EvaluationSchedule;
use App\Models\Evaluation;

class EvaluationFormController extends Controller
{
    /**
     * Function to get Evaluation Forms
     */
    public function index(Request $request)
    {
        try {
            // Get perPage
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
            // Get search
			$search = ($request->has('search')) ? '%'.$request->input('search').'%' : '%';

            // Get data
            $forms = EvaluationForm::
                whereNested(function ($query) use ($search) {
                    $query->where('form_title', 'LIKE', $search);
                })
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Evaluation Forms.',
                'evaluation_forms' => $forms
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Function to create Evaluation Form
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Request Validation
            $validator = Validator::make($request->all(), [
                'form_title' => 'string|unique:tbl_evaluation_forms,form_title|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Create
            $sched = EvaluationForm::create($request->all());
            $sched->created_by = Auth::user()->user_id; // Save the logged in user
            $sched->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating Evaluation Form.',
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }
    
    /**
     * Function to update Evaluation Form
     */
    public function update(Request $request, $evalFormId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationForm::where('eval_form_id', $evalFormId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form not found.'
                ]);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'form_title' => "string|unique:tbl_evaluation_forms,form_title,{$evalFormId},eval_form_id|required",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Update
            $form = EvaluationForm::find($evalFormId);
            $form->form_title = $request->input('form_title');
            $form->updated_by = Auth::user()->user_id; // Save the logged in user
            $form->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Updating Evaluation Form.'
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to delete Evaluation Form
     */
    public function delete(Request $request, $evalFormId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationForm::where('eval_form_id', $evalFormId)->doesntExist()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Success in Deleting Evaluation Form.'
                ], 200);
            }

            // Conflict validation
            // Evaluation Schedules Conflict
            if (EvaluationSchedule::where('eval_form_id', $evalFormId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form is already used in schedule.'
                ]);
            }
            
            // Evaluations Conflict
            if (Evaluation::where('eval_form_id', $evalFormId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form is already used to evaluate.'
                ]);
            }
            
            // Delete
            $form = EvaluationForm::find($evalFormId);
            $form->deleted_by = Auth::user()->user_id; // Save the logged in user
            $form->save();
            $form->delete();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deleting Evaluation Form.'
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to get Evaluation Form
     */
    public function show(Request $request, $evalFormId)
    {
        try {
            // Resource validation
            if (EvaluationForm::where('eval_form_id', $evalFormId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form not found.'
                ]);
            }

            // Get form
            $form = EvaluationForm::with('categories', 'categories.components')->find($evalFormId);

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Evaluation Form.',
                'evaluation_form' => $form
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }
}
