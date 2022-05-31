<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Illuminate\Database\QueryException;
use App\Models\EvalformCategory;
use App\Models\EvaluationForm;
use App\Models\Evaluation;
use App\Models\EvalformComponent;

class EvalformCategoryController extends Controller
{
    /**
     * Function to get Evaluation Categories
     */
    public function index(Request $request, $evalFormId)
    {
        try {
            // Resource validation
            if (EvaluationForm::where('eval_form_id', $evalFormId)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form not found.'
                ]);
            }

            // Get perPage
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
            // Get search
			$search = ($request->has('search')) ? '%'.$request->input('search').'%' : '%';
            // Get filters
            $filters = [];
            $filters['eval_form_id'] = $evalFormId;

            // Get data
            $categories = EvalformCategory::
                where($filters)
                ->whereNested(function ($query) use ($search) {
                    $query->where('desc', 'LIKE', $search);
                })
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Evaluation Form Categories.',
                'evaluation_categories' => $categories
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Function to create Evalform Category
     */
    public function store(Request $request, $evalFormId)
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
                'desc' => 'string|required',
                'percentage' => 'numeric|gt:0|lte:100|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Percentage Validation
            $curTotalPerc = EvalformCategory::where('eval_form_id', $evalFormId)->sum('percentage');
            $percentage = doubleval($request->input('percentage'));
            if ($percentage + $curTotalPerc > 100) {
                $remaining = 100 - $curTotalPerc;
                return response()->json([
                    'success' => false,
                    'message' => "Exceeds the remaining percentage ({$remaining}%)."
                ]);
            }
            
            // Create
            $request->request->add(['eval_form_id' => $evalFormId]);
            $category = EvalformCategory::create($request->all());
            $category->created_by = Auth::user()->user_id; // Save the logged in user
            $category->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating Evaluation Form Category.',
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to update Evalform Category
     */
    public function update(Request $request, $evalFormId, $evalformCategoryId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformCategory::where(['eval_form_id' => $evalFormId, 'evalform_category_id' => $evalformCategoryId])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Category not found.'
                ], 200);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'desc' => 'string|required',
                'percentage' => 'numeric|gt:0|lte:100|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Percentage Validation
            $curTotalPerc = EvalformCategory::
                where('eval_form_id', $evalFormId)
                ->where('evalform_category_id', '!=', $evalformCategoryId)
                ->sum('percentage');
            $percentage = doubleval($request->input('percentage'));
            if ($percentage + $curTotalPerc > 100) {
                $remaining = 100 - $curTotalPerc;
                return response()->json([
                    'success' => false,
                    'message' => "Exceeds the total percentage."
                ]);
            }
            
            // Create
            $category = EvalformCategory::find($evalformCategoryId);
            $category->desc = $request->input('desc');
            $category->percentage = $request->input('percentage');
            $category->updated_by = Auth::user()->user_id; // Save the logged in user
            $category->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Updating Evaluation Form Category.',
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to delete Evalform Category
     */
    public function delete(Request $request, $evalFormId, $evalformCategoryId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (EvalformCategory::where(['eval_form_id' => $evalFormId, 'evalform_category_id' => $evalformCategoryId])->doesntExist()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Success in Deleting Evaluation Form Category.'
                ], 200);
            }

            // Conflict validation
            // Evaluations Conflict
            if (Evaluation::where('eval_form_id', $evalFormId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation Form is already used to evaluate. You cannot delete this category.'
                ]);
            }

            // Delete components
            EvalformComponent::where('evalform_category_id', $evalformCategoryId)->update([
                'deleted_by' => 1
            ]);
            EvalformComponent::where('evalform_category_id', $evalformCategoryId)->delete();
            
            // Delete
            $category = EvalformCategory::find($evalformCategoryId);
            $category->deleted_by = Auth::user()->user_id; // Save the logged in user
            $category->save();
            $category->delete();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deleting Evaluation Form Category.'
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    /**
     * Function to get Evalform Category
     */
    public function show(Request $request, $evalFormId, $evalformCategoryId)
    {
        try {
            // Resource validation
            if (EvalformCategory::where(['eval_form_id' => $evalFormId, 'evalform_category_id' => $evalformCategoryId])->doesntExist()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Evaluation Form Category not found.'
                ], 200);
            }

            // Get data
            $category = EvalformCategory::with('components')->find($evalformCategoryId);

            return response()->json([
                'success' => true, 
                'message' => 'Success in Getting Evaluation Form Category.',
                'evalform_category' => $category
            ], 200);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json($e, 500);
        }
    }
}
