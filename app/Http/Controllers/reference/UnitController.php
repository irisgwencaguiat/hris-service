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
use App\Models\RefUnit;
use App\Models\RefDepartment;

class UnitController extends Controller
{
    // get units
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_at' => 'ASC']
        );

        $filters = apiFilters($request, [
            'office_id' => 'office_id',
            'department_id' => 'department_id'
        ], [], 'STRICT');
        
        // get data
        $units = RefUnit::
            with('office', 'department')
            ->where($filters)
            ->whereNested(function ($query) use ($search) {
                $query->where('unit_name', 'LIKE', $search);
            })
            ->whereHas('office')
            ->whereHas('department')
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Unit Reference', 
            'View Units', 
            'Success in Getting Units.'
        );

        return customResponse()
            ->message('Success in Getting Units.')
            ->data($units)
            ->success()
            ->generate();
    }

    // create unit
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'unit_name' => 'string|
                unique:ref_units,unit_name,NULL,unit_id,deleted_at,NULL|
                required',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable',
            'department_id' => 'exists:ref_departments,department_id,deleted_at,NULL|required_without:parent_unit_id',
            'have_sub_units' => 'boolean|required',
            'parent_unit_id' => 'exists:ref_units,unit_id,deleted_at,NULL|nullable',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Unit Reference', 
                'Create Unit', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        if ($request->input('department_id') === null) {
            $request->request->add([
                'department_id' => RefUnit::find($request->input('parent_unit_id'))->department_id
            ]);
        }

        if ($request->input('office_id') === null) {
            $request->request->add([
                'office_id' => RefDepartment::find($request->input('department_id'))->office_id
            ]);
        }

        RefUnit::create($request->all());

        $this->logActivity(
            'Unit Reference', 
            'Create Unit', 
            'Success in Creating Unit.'
        );

        return customResponse()
            ->message('Success in Creating Unit.')
            ->success(201)
            ->generate();
    }

    // update unit
    public function update(Request $request, $unitCode)
    {
        // resource validation
        $unit = RefUnit::find($unitCode);

        if ($unit === null) {
            $this->logActivity(
                'Unit Reference',
                'Update Unit',
                'Unit not found.'
            );

            return customResponse()
                ->message('Unit not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'unit_name' => 'string|required',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable',
            'department_id' => 'exists:ref_departments,department_id,deleted_at,NULL|required_without:parent_unit_id',
            'have_sub_units' => 'boolean|required',
            'parent_unit_id' => 'exists:ref_units,unit_id,deleted_at,NULL|nullable',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Unit Reference', 
                'Update Unit', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // update
        if ($request->input('department_id') === null) {
            $request->request->add([
                'department_id' => RefUnit::find($request->input('parent_unit_id'))->department_id
            ]);
        }

        if ($request->input('office_id') === null) {
            $request->request->add([
                'office_id' => RefDepartment::find($request->input('department_id'))->office_id
            ]);
        }
        
        $unit->update($request->all());

        $unit->updated_by = Auth::user()->user_id;
        $unit->save();

        $this->logActivity(
            'Unit Reference', 
            'Update Unit', 
            'Success in Updating Unit.
        ');

        return customResponse()
            ->message('Success in Updating Unit.')
            ->success()
            ->generate();
    }

    // delete unit
    public function delete(Request $request, $unitCode)
    {
        // resource validation
        $unit = RefUnit::find($unitCode);

        if ($unit === null) {
            $this->logActivity(
                'Unit Reference',
                'Delete Unit',
                'Unit not found.'
            );

            return customResponse()
                ->message('Unit not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $unit->deleted_by = Auth::user()->user_id;
        $unit->save();

        $unit->delete();

        $this->logActivity(
            'Unit Reference',
            'Delete Unit',
            'Success in Deleting Unit.'
        );

        return customResponse()
            ->message('Success in Deleting Unit')
            ->success()
            ->generate();
    }

    // get unit
    public function show(Request $request, $unitCode)
    {
        // resource validation
        $unit = RefUnit::find($unitCode);

        if ($unit === null) {
            $this->logActivity(
                'Unit Reference',
                'Get Unit',
                'Unit not found.'
            );

            return customResponse()
                ->message('Unit not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $unit = RefUnit::with(
            'sub_units'
        )->find($unitCode);

        $this->logActivity(
            'Unit Reference',
            'Get Unit',
            'Success in Getting Unit.'
        );

        return customResponse()
            ->message('Success in Getting Unit.')
            ->data($unit)
            ->success()
            ->generate();
    }
}
