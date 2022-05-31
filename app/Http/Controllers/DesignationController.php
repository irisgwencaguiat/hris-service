<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index() {
        $this->logEvent(Auth::user()->id, 'Visited designations page', trans('responses.status.success'));

        return Designation::with('Employee')->latest()->orderBy('level')->paginate(5);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'min:4|required|string',
            'description' => 'min:10|required|string',
            'level' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        $designation = new Designation($request->all());
        $designation->save();

        $this->logEvent(Auth::user()->id, 'Designation created named '.$request->name.', has a level of '.$request->level.' and describes as '.$request->description.'.', trans('responses.status.success'));

        return response()
            ->json(['status' => 'success', 'message' => 'A new designation has been added'], 201);
    }

    public function show($id) {
        $designation = Designation::with('Employee')->findOrFail($id);

        $this->logEvent(Auth::user()->id, 'Viewed '.$designation->name.' designation.', trans('responses.status.success'));

        return response()
            ->json($designation);
    }

    public function update($id, Request $request) {
        $designation = Designation::with('Employee')->findOrFail($id);

        $designation->update($request->all());

        $this->logEvent(Auth::user()->id, 'Updated the designation '.$designation->getOriginal('name').' to '.$designation->name.'.', trans('responses.status.success'));

        return response()
            ->json(['status' => 'success', 'message' => 'Designation has been updated']);
    }
}
