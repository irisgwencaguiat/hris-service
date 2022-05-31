<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request) {
        $per_page = $request->input('perPage', 5);
        $page = $request->input('page', 1);

        $this->logEvent(Auth::user()->user_id, 'Visited logs page', trans('responses.status.success'));

        return Log::with('user.type')->latest()->paginate($per_page, ['*'], 'page', $page);
    }

    public function show($id) {
        $log = Log::with('user.type')->findOrFail($id);

        $this->logEvent(Auth::user()->user_id, 'Viewed log id: '.$log->id.'.', trans('responses.status.success'));

        return response()->json($log);
    }
}
