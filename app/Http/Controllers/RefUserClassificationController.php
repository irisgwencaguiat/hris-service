<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Database\QueryException;
use App\Models\RefUserClassification;

class RefUserClassificationController extends Controller
{
    /**
     * Function to get User Classification
     */
    public function index(Request $request)
    {
        try {
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
			$search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';
            $sortBy = apiSortBy($request->input('sort_by'), $request->input('sort_desc'), [], [
                'user_classification_name' => 'DESC',
            ]);
            
            // Get data
            $classifications = RefUserClassification::
                whereNested(function ($query) use ($search) {
                    $query->where('user_classification_id', 'LIKE', $search)
                        ->orWhere('user_classification_name', 'LIKE', $search);
                })
                ->when($sortBy, function ($query, $sortBy) {
                    foreach($sortBy as $column => $order)
                        $query->orderBy($column, $order);
                })
                ->paginate($perPage);
            
            $this->logActivity('User Classifications', 'View User Classifications', 'Success in Getting User Classifications.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting User Classifications.',
                'user_classifications' => $classifications
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Classifications', 'View User Classifications', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }
}
