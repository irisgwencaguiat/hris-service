<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Illuminate\Database\QueryException;
use App\Models\EvalformCategory;
use App\Models\EvalformComponent;
use App\Models\EvalformMajorFinalOutput;
use App\Models\EvalformSuccessIndicator;
use App\Models\EvalformActualAccomplishment;

class EvalformComponentController extends Controller
{
    /**
     * Function to get Evalform Components
     */
    public function index(Request $request, $evalFormId, $evalformCategoryId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformCategory::where(['eval_form_id' => $evalFormId, 'evalform_category_id' => $evalformCategoryId])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Category not found.'
                ], 404);
            }

            // Get perPage
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
            // Get search
			$search = ($request->has('search')) ? '%'.$request->input('search').'%' : '%';
            // Get filters
            $filters = [];
            $filters['eval_form_id'] = $evalFormId;
            $filters['evalform_category_id'] = $evalformCategoryId;

            // Get data
            $components = EvalformComponent::where($filters)
                ->whereNested(function ($query) use ($search) {
                    // $query->where('desc', 'LIKE', $search);
                })
                ->paginate($perPage);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Getting Evaluation Form Components.',
                'evalform_components' => $components
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to create Evalform Component
     */
    public function store(Request $request, $evalFormId, $evalformCategoryId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformCategory::where(['eval_form_id' => $evalFormId, 'evalform_category_id' => $evalformCategoryId])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Category not found.'
                ], 404);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'major_final_output' => 'string|required',
                'success_indicator' => 'string|required',
                'actual_accomplishment' => 'string|nullable',
                'need_comment' => 'boolean|required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Create
            $majorFinalOutputId = EvalformMajorFinalOutput::firstOrCreate(['desc' => $request->input('major_final_output')])->evalform_major_final_output_id;
            $successIndicatorId = EvalformSuccessIndicator::firstOrCreate(['desc' => $request->input('success_indicator')])->evalform_success_indicator_id;
            $actualAccomplishmentId = ($request->input('actual_accomplishment') == null) ? null :
                EvalformMajorFinalOutput::firstOrCreate(['desc' => $request->input('actual_accomplishment')])->evalform_actual_accomplishment_id;
            $request->request->add(['eval_form_id' => $evalFormId]);
            $request->request->add(['evalform_category_id' => $evalformCategoryId]);
            $request->request->add(['evalform_major_final_output_id' => $majorFinalOutputId]);
            $request->request->add(['evalform_success_indicator_id' => $successIndicatorId]);
            $request->request->add(['evalform_actual_accomplishment_id' => $actualAccomplishmentId]);
            $component = EvalformComponent::create($request->all());
            $component->created_by = Auth::user()->user_id; // Save the logged in user
            $component->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating Evaluation Form Component.',
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to update Evalform Component
     */
    public function update(Request $request, $evalFormId, $evalformCategoryId, $evalformComponentId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformComponent::where([
                'eval_form_id' => $evalFormId, 
                'evalform_category_id' => $evalformCategoryId,
                'evalform_component_id' => $evalformComponentId
            ])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Component not found.'
                ], 404);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'major_final_output' => 'string|required',
                'success_indicator' => 'string|required',
                'actual_accomplishment' => 'string|nullable',
                'need_comment' => 'boolean|required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Update
            $majorFinalOutputId = EvalformMajorFinalOutput::firstOrCreate(['desc' => $request->input('major_final_output')])->evalform_major_final_output_id;
            $successIndicatorId = EvalformSuccessIndicator::firstOrCreate(['desc' => $request->input('success_indicator')])->evalform_success_indicator_id;
            $actualAccomplishmentId = ($request->input('actual_accomplishment') == null) ? null :
                EvalformMajorFinalOutput::firstOrCreate(['desc' => $request->input('actual_accomplishment')])->evalform_actual_accomplishment_id;
            $component = EvalformComponent::find($evalformComponentId);
            $component->evalform_major_final_output_id = $majorFinalOutputId;
            $component->evalform_success_indicator_id = $successIndicatorId;
            $component->evalform_actual_accomplishment_id = $actualAccomplishmentId;
            $component->updated_by = Auth::user()->user_id; // Save the logged in user
            $component->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Updating Evaluation Form Component.',
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

     /**
     * Function to delete Evalform Component
     */
    public function delete(Request $request, $evalFormId, $evalformCategoryId, $evalformComponentId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformComponent::where([
                'eval_form_id' => $evalFormId, 
                'evalform_category_id' => $evalformCategoryId,
                'evalform_component_id' => $evalformComponentId
            ])->doesntExist()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Success in Deleting Evaluation Form Component.'
                ], 200);
            }

            // Delete
            $component = EvalformComponent::find($evalformComponentId);
            $component->deleted_by = Auth::user()->user_id; // Save the logged in user
            $component->save();
            $component->delete();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deleting Evaluation Form Component.',
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to Get Evalform Component
     */
    public function show(Request $request, $evalFormId, $evalformCategoryId, $evalformComponentId)
    {
        try {
            // Resource validation
            if (EvalformComponent::where([
                'eval_form_id' => $evalFormId, 
                'evalform_category_id' => $evalformCategoryId,
                'evalform_component_id' => $evalformComponentId
            ])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Component not found.'
                ], 404);
            }

            // Get data
            $component = EvalformComponent::find($evalformComponentId);

            return response()->json([
                'success' => true, 
                'message' => 'Success in Getting Evaluation Form Component.',
                'evalform_component' => $component
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }
}
