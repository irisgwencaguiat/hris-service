<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Illuminate\Database\QueryException;
use App\Models\UserActivityLog;

class UserActivityLogController extends Controller
{
    /**
     * Function to get User Activity logs
     */
    public function index(Request $request)
    {
        try {
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
			$search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';
            $sortBy = apiSortBy($request->input('sort_by'), $request->input('sort_desc'), [], [
                'logged_at' => 'DESC',
            ]);
            
            // Get data
            $logs = UserActivityLog::
                whereNested(function ($query) use ($search) {
                    $query->where('type', 'LIKE', $search)
                        ->orWhere('action', 'LIKE', $search)
                        ->orWhere('remarks', 'LIKE', $search);
                })
                ->when($sortBy, function ($query, $sortBy) {
                    foreach($sortBy as $column => $order)
                        $query->orderBy($column, $order);
                })
                ->paginate($perPage);
            
            $this->logActivity('User Activity Logs', 'View User Activity Logs', 'Success in Getting User Activity Logs.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting User Activity Logs.',
                'user_activity_logs' => $logs
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Activity Logs', 'View User Activity Logs', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to logged an activity
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Request Validation
            $validator = Validator::make($request->all(), [
                'type' => 'string|required',
                'action' => 'string|nullable',
                'remarks' => 'string|nullable'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please send the logs data properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Create
            $this->logActivity(
                $request->input('type'), 
                $request->input('action'),
                $request->input('remarks')
            );

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating User Activity Log.',
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }
}
