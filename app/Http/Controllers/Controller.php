<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Log;
use App\Models\UserActivityLog;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logEvent($user_id, $action, $status) {
        $user = User::with('type', 'classification')->findOrFail($user_id);

        $logs = new Log([
            'action_taken' => $action,
            'status' => $status
        ]);

        $user->logs()->save($logs);
    }

    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $character_length = strlen($characters);
        $random_string = '';

        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[mt_rand(0, $character_length - 1)];
        }

        return $random_string;
    }

    public function confirmPassword(Request $request) {
        $developer_password = User::where('id', '=', 1)->first()->password;

        $compare_password = Hash::check($request->password, $developer_password);

        if (!$compare_password) {
            $this->logEvent(Auth::user()->id, 'An attempt to do an action with password confirmation which is an administrative and may affect the system.', trans('responses.status.error'));

            return response()->json(['status' => 'error', 'message' => 'The authentication password is invalid!'], 400);
        }

        return true;
    }

    /**
     * Function to call the creation of activity log
     */
    public function logActivity($type, $action = '', $remarks = '')
    {
        UserActivityLog::create([
            'user_id' => Auth::user()->user_id,
            'type' => $type,
            'action' => $action,
            'remarks' => $remarks
        ]);
    }
}
