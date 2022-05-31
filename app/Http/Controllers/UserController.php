<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Hash;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserType;
use App\Models\RefUserClassification;

class UserController extends Controller
{
    /**
     * Function to get Users
     */
    public function index(Request $request)
    {
        try {
            $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);
			$search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';
            $sortBy = apiSortBy($request->input('sort_by'), $request->input('sort_desc'), [], [
                'created_at' => 'DESC',
            ]);
            
            // Get data
            $users = User::
                with([
                    'profile',
                    'profile.userType',
                    'profile.userClassification'
                ])
                ->whereNested(function ($query) use ($search) {
                    $query->where('username', 'LIKE', $search);
                })
                ->orWhereHas('profile', function ($query) use ($search) {
                    $query->where('last_name', 'LIKE', $search)
                        ->orWhere('first_name', 'LIKE', $search);
                })
                ->orWhereHas('profile.userType', function ($query) use ($search) {
                    $query->where('user_type_name', 'LIKE', $search);
                })
                ->orWhereHas('profile.userClassification', function ($query) use ($search) {
                    $query->where('user_classification_name', 'LIKE', $search);
                })
                ->when($sortBy, function ($query, $sortBy) {
                    foreach($sortBy as $column => $order)
                        $query->orderBy($column, $order);
                })
                ->paginate($perPage);
            
            $this->logActivity('User Account', 'View User Accounts', 'Success in Getting Users.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Users.',
                'users' => $users
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Account', 'View User Accounts', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Create User
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Request Validation
            $validator = Validator::make($request->all(), [
                'username' => 'string|unique:tbl_users,username|required',
                'password' => 'string|required',
                'user_type_id' => 'exists:tbl_user_types,user_type_id,deleted_at,NULL|required',
                'user_classification_id' => 'exists:ref_user_classifications,user_classification_id,deleted_at,NULL|nullable',
                'last_name' => 'string|required',
                'first_name' => 'string|required',
                'middle_name' => 'string|nullable',
                'suffix' => 'string|nullable',
                'email' => 'email|unique:tbl_user_profiles,email|nullable'
            ]);
            if ($validator->fails()) {
                DB::rollback();
                $this->logActivity('User Account', 'Create User Account', 'Please send the user data properly.');

                return response()->json([
                    'success' => false, 
                    'message' => 'Please send the user data properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Create
            $request->request->add(['password' => Hash::make($request->input('password'))]);
            $user = User::create($request->all());
            $user->created_by = Auth::user()->user_id; // Change to Auth()->user_id
            $user->save();

            $request->request->add(['user_id' => $user->user_id]);
            UserProfile::create($request->all());

            DB::commit();

            $this->logActivity('User Account', 'Create User Account', 'Success in Creating User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Creating User Account.',
                'user' => $user
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Create User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to get User
     */
    public function show(Request $request, $userId)
    {
        try {
            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'View User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }

            // Get data
            $user = User::with([
                'profile',
                'profile.userType',
                'profile.userClassification'
            ])
            ->find($userId);
        
            $this->logActivity('User Account', "View User Account: {$user->username}", 'Success in Getting User.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting User.',
                'user' => $user
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Account', 'View User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Update User
     */
    public function update(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Update User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'username' => "string|unique:tbl_users,username,{$userId},user_id|required",
                'password' => 'string|required',
                'user_type_id' => 'exists:tbl_user_types,user_type_id,deleted_at,NULL|required',
                'user_classification_id' => 'exists:ref_user_classifications,user_classification_id,deleted_at,NULL|nullable',
                'last_name' => 'string|required',
                'first_name' => 'string|required',
                'middle_name' => 'string|nullable',
                'suffix' => 'string|nullable',
                'email' => 'email|unique:tbl_user_profiles,email|nullable'
            ]);
            if ($validator->fails()) {
                DB::rollback();
                $this->logActivity('User Account', 'Update User Account', 'Please send the user data properly.');
                return response()->json([
                    'success' => false, 
                    'message' => 'Please send the user data properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Update
            $request->request->add(['password' => Hash::make($request->input('password'))]);
            $user = User::find($userId);
            $user->update($request->all());
            $user->updated_by = Auth::user()->user_id; // Change to Auth()->user_id
            $user->save();

            $user->profile->update($request->all());
            $user->profile->updated_by = Auth::user()->user_id; // Change to Auth()->user_id
            $user->profile->save();

            DB::commit();

            $this->logActivity('User Account', "Update User Account: {$user->username}", 'Success in Updating User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Updating User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Update User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Delete User
     */
    public function delete(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Delete User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            
            // Delete
            $user = User::find($userId);
            $user->deleted_by = Auth::user()->user_id; // Change to Auth::user()->user_id
            $user->save();
            $user->delete();

            DB::commit();

            $this->logActivity('User Account', "Delete User Account: {$user->username}", 'Success in Deleting User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deleting User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Delete User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Deactivate User
     */
    public function deactivate(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Deactivate User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            if (!User::find($userId)->activated) {
                DB::rollback();
                $this->logActivity('User Account', 'Deactivate User Account', 'User Account is already deactivated.');
                return response()->json([
                    'success' => false,
                    'message' => 'User Account is already deactivated.'
                ]);
            }
            
            // Deactivate
            $user = User::find($userId);
            $user->activated = false;
            $user->deactivated_by = Auth::user()->user_id; // Change to
            $user->deactivated_at = Carbon::now();
            $user->save();

            DB::commit();

            $this->logActivity('User Account', "Deactivate User Account: {$user->username}", 'Success in Deactivating User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Deactivating User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Deactivate User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Activate User
     */
    public function activate(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Activate User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            if (User::find($userId)->activated) {
                DB::rollback();
                $this->logActivity('User Account', 'Activate User Account', 'User Account is already deactivated.');
                return response()->json([
                    'success' => false,
                    'message' => 'User Account is already activated.'
                ]);
            }
            
            // Activate
            $user = User::find($userId);
            $user->activated = true;
            $user->activated_by = Auth::user()->user_id; // Change to
            $user->activated_at = Carbon::now();
            $user->save();

            DB::commit();

            $this->logActivity('User Account', "Activate User Account: {$user->username}", 'Success in Activating User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Activating User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Activate User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Reset Password
     */
    public function resetPassword(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            //Resource validation
            if(User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Reset Password', 'User not Found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            
            // Reset Password
            $user = User::find($userId);
            $user->password = Hash::make($request->input('username'));
            $user->save();

            DB::commit();

            $this->logActivity('User Account', "Reset Password: {$user->username}", 'Success in Resetting password.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Resetting password.'
            ], 201);

        }catch(QueryException $e){
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Reset Password', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to Ban User
     */
    public function ban(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Ban User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            if (User::find($userId)->banned) {
                DB::rollback();
                $this->logActivity('User Account', 'Ban User Account', 'User Account is already banned.');
                return response()->json([
                    'success' => false,
                    'message' => 'User Account is already banned.'
                ]);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'banned_reason' => 'string|required',
            ]);
            if ($validator->fails()) {
                DB::rollback();
                $this->logActivity('User Account', 'Ban User Account', 'Please fill out the form properly.');
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Ban
            $user = User::find($userId);
            $user->banned = true;
            $user->banned_reason = $request->input('banned_reason');
            $user->banned_by = Auth::user()->user_id; // Change to
            $user->banned_at = Carbon::now();
            $user->save();

            DB::commit();

            $this->logActivity('User Account', "Ban User Account: {$user->username}", 'Success in Banning User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Banning User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Ban User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to add Expiration to User
     */
    public function expire(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            // Resource validation
            if (User::where('user_id', $userId)->doesntExist()) {
                DB::rollback();
                $this->logActivity('User Account', 'Expire User Account', 'User not found.');
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }

            // Request Validation
            $validator = Validator::make($request->all(), [
                'expired_at' => 'date|required',
            ]);
            if ($validator->fails()) {
                DB::rollback();
                $this->logActivity('User Account', 'Expire User Account', 'Please fill out the form properly.');
                return response()->json([
                    'success' => false, 
                    'message' => 'Please fill out the form properly.',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            // Expire
            $newExpiredAt = Carbon::parse($request->input('expired_at'));
            $user = User::find($userId);
            $user->expired = ($newExpiredAt <= Carbon::now());
            $user->expired_by = Auth::user()->user_id; // Change to
            $user->expired_at = $newExpiredAt;
            $user->save();

            DB::commit();

            $this->logActivity('User Account', "Expire User Account: {$user->username}", 'Success in Expiring User Account.');

            return response()->json([
                'success' => true, 
                'message' => 'Success in Expiring User Account.'
            ], 201);
        }
        catch (QueryException $e) {
            Log::debug($e);
            DB::rollback();
            $this->logActivity('User Account', 'Expire User Account', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to get Statistics Count
     */
    public function statisticsCount(Request $request)
    {
        try {
            $statistics = DB::table('tbl_users AS u')
                ->selectRaw("
                    SUM(CASE WHEN u.activated = 1 THEN 1 ELSE 0 END) AS activated_count,
                    SUM(CASE WHEN u.activated = 0 THEN 1 ELSE 0 END) AS deactivated_count,
                    SUM(CASE WHEN u.expired = 1 THEN 1 ELSE 0 END) AS expired_count,
                    SUM(CASE WHEN u.banned = 1 THEN 1 ELSE 0 END) AS banned_count
                ")
                ->first();

            $statistics->total = 
                $statistics->activated_count
                + $statistics->deactivated_count
                + $statistics->expired_count
                + $statistics->banned_count;

            $this->logActivity('User Statistics', 'Statistics Count', 'Success in Getting Statistics Count.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Statistics Count.',
                'statistics' => $statistics
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Statistics', 'Statistics Count', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to get Statistics Count by User Types
     */
    public function statisticsCountByTypes(Request $request)
    {
        try {
            // Get data
            $userTypes = UserType::get();
            $totals = (object)[
                'activated_count' => 0,
                'deactivated_count' => 0,
                'expired_count' => 0,
                'banned_count' => 0,
            ];

            foreach ($userTypes as $userType) {
                $statistics = DB::table('tbl_users AS u')
                    ->join('tbl_user_profiles as up', 'up.user_id', 'u.user_id')
                    ->selectRaw("
                        SUM(CASE WHEN u.activated = 1 THEN 1 ELSE 0 END) AS activated_count,
                        SUM(CASE WHEN u.activated = 0 THEN 1 ELSE 0 END) AS deactivated_count,
                        SUM(CASE WHEN u.expired = 1 THEN 1 ELSE 0 END) AS expired_count,
                        SUM(CASE WHEN u.banned = 1 THEN 1 ELSE 0 END) AS banned_count
                    ")
                    ->where('up.user_type_id', $userType->user_type_id)
                    ->first();

                if ($statistics->activated_count == null) $statistics->activated_count = 0;
                if ($statistics->deactivated_count == null) $statistics->deactivated_count = 0;
                if ($statistics->expired_count == null) $statistics->expired_count = 0;
                if ($statistics->banned_count == null) $statistics->banned_count = 0;
                
                $statistics->total = 
                    $statistics->activated_count
                    + $statistics->deactivated_count
                    + $statistics->expired_count
                    + $statistics->banned_count;

                $totals->activated_count += $statistics->activated_count;
                $totals->deactivated_count += $statistics->deactivated_count;
                $totals->expired_count += $statistics->expired_count;
                $totals->banned_count += $statistics->banned_count;

                $userType->statistics = $statistics;
            }

            $totals->total = 0;
            foreach($totals as $key => $value) {
                if ($key == 'total') continue;

                $totals->total += $value;
            }

            $this->logActivity('User Statistics', 'Statistics Count By User Types', 'Success in Getting Statistics Count By User Types.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Statistics Count By User Types.',
                'statistics' => (object)[
                    'user_types' => $userTypes,
                    'totals' => $totals
                ]
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Statistics', 'Statistics Count By User Types', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }

    /**
     * Function to get Statistics Count by User Classifications
     */
    public function statisticsCountByClassifications(Request $request)
    {
        try {
            // Get data
            $classifications = RefUserClassification::get();
            $totals = (object)[
                'activated_count' => 0,
                'deactivated_count' => 0,
                'expired_count' => 0,
                'banned_count' => 0,
            ];

            foreach ($classifications as $class) {
                $statistics = DB::table('tbl_users AS u')
                    ->join('tbl_user_profiles as up', 'up.user_id', 'u.user_id')
                    ->selectRaw("
                        SUM(CASE WHEN u.activated = 1 THEN 1 ELSE 0 END) AS activated_count,
                        SUM(CASE WHEN u.activated = 0 THEN 1 ELSE 0 END) AS deactivated_count,
                        SUM(CASE WHEN u.expired = 1 THEN 1 ELSE 0 END) AS expired_count,
                        SUM(CASE WHEN u.banned = 1 THEN 1 ELSE 0 END) AS banned_count
                    ")
                    ->where('up.user_classification_id', $class->user_classification_id)
                    ->first();

                if ($statistics->activated_count == null) $statistics->activated_count = 0;
                if ($statistics->deactivated_count == null) $statistics->deactivated_count = 0;
                if ($statistics->expired_count == null) $statistics->expired_count = 0;
                if ($statistics->banned_count == null) $statistics->banned_count = 0;
                
                $statistics->total = 
                    $statistics->activated_count
                    + $statistics->deactivated_count
                    + $statistics->expired_count
                    + $statistics->banned_count;

                $totals->activated_count += $statistics->activated_count;
                $totals->deactivated_count += $statistics->deactivated_count;
                $totals->expired_count += $statistics->expired_count;
                $totals->banned_count += $statistics->banned_count;

                $class->statistics = $statistics;
            }

            $totals->total = 0;
            foreach($totals as $key => $value) {
                if ($key == 'total') continue;
                
                $totals->total += $value;
            }

            $this->logActivity('User Statistics', 'Statistics Count By User Classifications', 'Success in Getting Statistics By User Classifications.');

            return response()->json([
                'success' => true,
                'message' => 'Success in Getting Statistics Count By User Classifications.',
                'statistics' => (object)[
                    'user_classifications' => $classifications,
                    'totals' => $totals
                ]
            ], 200);
        }
        catch(QueryException $e) {
            Log::debug($e);
            $this->logActivity('User Statistics', 'Statistics Count By User Classifications', 'Failed Request.');
            return response()->json(['success' => false, 'message' => 'Failed Request.'], 500);
        }
    }
}
