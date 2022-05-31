<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\UserType;

class UserTypeController extends Controller
{
    /**
     * Function to get User types
     */
    public function index(Request $request)
    {
        try {
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
			$search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';
            $sortBy = apiSortBy($request->input('sort_by'), $request->input('sort_desc'), [], [
                'created_at' => 'ASC',
            ]);
            
            // Get data
            $userTypes = UserType::
                whereNested(function ($query) use ($search) {
                    $query->where('user_type_id', 'LIKE', $search)
                        ->orWhere('user_type_name', 'LIKE', $search);
                })
                ->when($sortBy, function ($query, $sortBy) {
                    foreach($sortBy as $column => $order)
                        $query->orderBy($column, $order);
                })
                ->paginate($perPage);
            
            $this->logActivity('User Types', 'View User Types', 'Success in Getting User Types.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting User Types.',
                'user_types' => $userTypes
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Types', 'View User Types', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_type_name' => 'string|min:4|required'
        ]);

        if ($validator->fails()) {
            $this->logEvent(
                Auth::user()->id,
            'Attempted to create a new user type but failed with validator',
                trans('responses.status.error'));

            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 401);
        }

        $user_type = new UserType($request->all());

        $creator = User::with('type', 'classification')->findOrFail(Auth::user()->user_id);

        $user_type->creator()->associate($creator->user_id);

        $user_type->save();

        return response()->json([
            'status' => 'success',
            'message' => 'A new user type has been created'
        ], 201);

    }

    public function show($id) {
        $user_type = UserType::with('user', 'deleter', 'creator', 'updater')
            ->findOrFail($id);

        return response()->json($user_type);
    }

    public function update($id, Request $request) {
        $user_type = UserType::with('user', 'deleter', 'creator', 'updater')
            ->findOrFail($id);

        $user_type->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User type has been updated!'
        ]);
    }

    public function destroy($id) {
        $user_type = UserType::with('user', 'deleter', 'creator', 'updater')->findOrFail($id);

        $user_type->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User type has been removed'
        ]);
    }
}
