<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use App\Models\EvaluationSchedule;
use App\Models\Evaluation;
use App\Models\EvalformCategory;

class EvaluationScheduleController extends Controller
{
    /**
     * Function to get Evaluation Schedules
     */
    public function index(Request $request)
    {
        try {
            // Get perPage
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
            // Get filters
            $filters = apiFilters($request, ['eval_type' => 'eval_type']);
            // Get search
			$search = ($request->has('search')) ? '%'.$request->input('search').'%' : '%';

            // Get data
            $scheds = EvaluationSchedule::
                where($filters)
                ->whereNested(function ($query) use ($search) {
                    $query->where('eval_type', 'LIKE', $search)
                        ->orWhere('eval_index', 'LIKE', $search);
                })
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Evaluation Schedules.',
                'evaluation_schedules' => $scheds
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Function to create Evaluation Schedule
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Request Validation
            $validator = Validator::make($request->all(), [
                'eval_type' => 'exists:ref_evaluation_types,eval_type_id|required',
                'eval_display_start' => 'date|required',
                'eval_display_end' => 'date|after:eval_display_start|required',
                'date_start' => 'date|required',
                'date_end' => 'date|after:date_start|required',
                'eval_form_id' => 'exists:tbl_evaluation_forms,eval_form_id|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Evaluation Form percentage validation
            if (EvalformCategory::where('eval_form_id', $request->input('eval_form_id'))->sum('percentage') != 100) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Total percentage of the form does not meet 100%. Please select other form.'
                ]);
            }
            
            // Create
            $sched = EvaluationSchedule::create($request->all());
            $sched->created_by = Auth::user()->user_id; // Save the logged in user

            // Create evaluation index from eval display start
            $year = substr($sched->eval_display_start, 0, 4);
            $count = EvaluationSchedule::withTrashed()->where('eval_index', 'LIKE', "{$year}-%")->count();
            $sched->eval_index = "{$year}-".str_pad($count, 3, '0', STR_PAD_LEFT);

            $sched->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating Evaluation Schedule.',
                'eval_index' => $sched->eval_index
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to update Evaluation Schedule
     */
    public function update(Request $request, $evalSchedId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationSchedule::where('eval_sched_id', $evalSchedId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Schedule not found.'
                ]);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'eval_type' => 'exists:ref_evaluation_types,eval_type_id|required',
                'eval_display_start' => 'date|required',
                'eval_display_end' => 'date|after:eval_display_start|required',
                'date_start' => 'date|required',
                'date_end' => 'date|after:date_start|required',
                'eval_form_id' => 'exists:tbl_evaluation_forms,eval_form_id|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Evaluation Form percentage validation
            if (EvalformCategory::where('eval_form_id', $request->input('eval_form_id'))->sum('percentage') != 100) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Total percentage of the form does not meet 100%. Please select other form.'
                ]);
            }
            
            // Update
            $sched = EvaluationSchedule::find($evalSchedId);
            $sched->eval_type = $request->input('eval_type');
            $sched->eval_display_start = $request->input('eval_display_start');
            $sched->eval_display_end = $request->input('eval_display_end');
            $sched->date_start = $request->input('date_start');
            $sched->date_end = $request->input('date_end');
            $sched->eval_form_id = $request->input('eval_form_id');
            $sched->updated_by = Auth::user()->user_id; // Save the logged in user
            $sched->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Updating Evaluation Schedule.'
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to delete Evaluation Schedule
     */
    public function delete(Request $request, $evalSchedId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationSchedule::where('eval_sched_id', $evalSchedId)->doesntExist()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Success in Deleting Evaluation Schedule.'
                ], 200);
            }

            // Conflict validation
            // Check if evaluation schedule is already used in evaluation
            if (Evaluation::where('eval_sched_id', $evalSchedId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Schedule is already used.'
                ]);
            }
            
            // Delete
            $sched = EvaluationSchedule::find($evalSchedId);
            $sched->deleted_by = Auth::user()->user_id; // Save the logged in user
            $sched->save();
            $sched->delete();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deleting Evaluation Schedule.'
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to get Evaluation Indexes
     */
    public function getEvalIndexes(Request $request)
    {
        try {
            // Get perPage
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
            // Get filters
            $filters = apiFilters($request, ['eval_type' => 'eval_type']);
            // Get search
			$search = ($request->has('search')) ? '%'.$request->input('search').'%' : '%';

            // Get data
            $evalIndexes = EvaluationSchedule::
                where($filters)
                ->where('eval_index', 'LIKE', $search)
                ->select('eval_sched_id', 'eval_index')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Evaluation Indexes.',
                'eval_indexes' => $evalIndexes
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Function to activate Evaluation Schedule
     */
    public function activate(Request $request, $evalSchedId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationSchedule::where('eval_sched_id', $evalSchedId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Schedule not found.'
                ]);
            }

            // Evaluation Form validation
            $sched = EvaluationSchedule::find($evalSchedId);
            if ($sched->eval_form_id == null) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Please add evaluation form before activating.'
                ]);
            }
            // Evaluation Form percentage validation
            if (EvalformCategory::where('eval_form_id', $sched->eval_form_id)->sum('percentage') != 100) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Total percentage of the form does not meet 100%. Please add the remaining.'
                ]);
            }
            
            // Already Activated validation
            if (EvaluationSchedule::find($evalSchedId)->activated == true) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Evaluation Schedule is already activated.'
                ]);
            }

            // Deactivate the active schedule
            EvaluationSchedule::where('activated', true)->update([
                'activated' => false,
                'deactivated_by' => 1,
                'deactivated_at' => Carbon::now()
            ]);
            
            // Activate
            $sched = EvaluationSchedule::find($evalSchedId);
            $sched->activated = true;
            $sched->activated_by = Auth::user()->user_id;
            $sched->activated_at = Carbon::now();
            $sched->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => "Success in Activating Evaluation Schedule."
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }
    
    /**
     * Function to deactivate Evaluation Schedule
     */
    public function deactivate(Request $request, $evalSchedId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvaluationSchedule::where('eval_sched_id', $evalSchedId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Schedule not found.'
                ]);
            }

            // Already Deactivated validation
            if (EvaluationSchedule::find($evalSchedId)->activated == false) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Evaluation Schedule is already deactivated.'
                ]);
            }
            
            // Deactivate
            $sched = EvaluationSchedule::find($evalSchedId);
            $sched->activated = false;
            $sched->deactivated_by = Auth::user()->user_id;
            $sched->deactivated_at = Carbon::now();
            $sched->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => "Success in Deactivating Evaluation Schedule."
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to get Evaluation Schedule
     */
    public function show(Request $request, $evalSchedId)
    {
        try {
            // Resource validation
            if (EvaluationSchedule::where('eval_sched_id', $evalSchedId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Schedule not found.'
                ]);
            }

            // Get data
            $sched = EvaluationSchedule::find($evalSchedId);

            return response()->json([
                'success' => true, 
                'message' => 'Success in Getting Evaluation Schedule.',
                'evaluation_schedule' => $sched
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }
}
