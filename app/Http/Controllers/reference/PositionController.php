<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\RefPosition;

class PositionController extends Controller
{
    // get positions
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 10000);

        $search = $request->has('search')
            ? "%{$request->input('search')}%"
            : '%';

        $sortBy = apiSortBy(
            $request->input('sort_by'),
            $request->input('sort_desc'),
            [],
            ['created_at' => 'ASC']
        );

        // get data
        $positions = RefPosition::
            whereNested(function ($query) use ($search) {
                $query->where('position_name', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach ($sortBy as $column => $order) {
                    $query->orderBy($column, $order);
                }
            })
            ->paginate($perPage);

        $this->logActivity(
            'Position Reference',
            'View Positions',
            'Success in Getting Positions.'
        );

        return customResponse()
            ->message('Success in Getting Positions.')
            ->data($positions)
            ->success()
            ->generate();
    }

    // create position
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'position_name' =>
                'string|unique:ref_positions,position_name|required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Position Reference',
                'Create Position',
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // create
        RefPosition::create($request->all());

        $this->logActivity(
            'Position Reference',
            'Create Position',
            'Success in Creating Position.'
        );

        return customResponse()
            ->message('Success in Creating Position.')
            ->success(201)
            ->generate();
    }

    // update position
    public function update(Request $request, $positionCode)
    {
        // resource validation
        $position = RefPosition::find($positionCode);

        if ($position === null) {
            $this->logActivity(
                'Position Reference',
                'Update Position',
                'Position not found.'
            );

            return customResponse()
                ->message('Position not found.')
                ->success(404)
                ->generate();
        }

        // request validation
        $validator = Validator::make($request->all(), [
            'position_name' => "string|
                unique:ref_positions,position_name,{$positionCode},position_id|
                required",
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Position Reference',
                'Update Position',
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $position->update($request->all());

        $position->updated_by = Auth::user()->user_id;
        $position->save();

        $this->logActivity(
            'Position Reference',
            'Update Position',
            'Success in Updating Position.
        '
        );

        return customResponse()
            ->message('Success in Updating Position.')
            ->success()
            ->generate();
    }

    // delete position
    public function delete(Request $request, $positionCode)
    {
        // resource validation
        $position = RefPosition::find($positionCode);

        if ($position === null) {
            $this->logActivity(
                'Position Reference',
                'Delete Position',
                'Position not found.'
            );

            return customResponse()
                ->message('Position not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $position->deleted_by = Auth::user()->user_id;
        $position->save();

        $position->delete();

        $this->logActivity(
            'Position Reference',
            'Delete Position',
            'Success in Deleting Position.'
        );

        return customResponse()
            ->message('Success in Deleting Position')
            ->success()
            ->generate();
    }

    // get position
    public function show(Request $request, $positionCode)
    {
        // resource validation
        $position = RefPosition::find($positionCode);

        if ($position === null) {
            $this->logActivity(
                'Position Reference',
                'Get Position',
                'Position not found.'
            );

            return customResponse()
                ->message('Position not found.')
                ->failed(404)
                ->generate();
        }

        // return
        $this->logActivity(
            'Position Reference',
            'Get Position',
            'Success in Getting Position.'
        );

        return customResponse()
            ->message('Success in Getting Position.')
            ->data($position)
            ->success()
            ->generate();
    }
}
