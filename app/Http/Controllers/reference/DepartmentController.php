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
use App\Models\RefDepartment;

class DepartmentController extends Controller
{
    // get departments
    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->filterables([
                'office_id'
            ])
            ->generate();

        // get data
        $departments = RefDepartment::
            with('office')
            ->whereHas('office')
            ->usingAPIParam($apiParam);

        return customResponse()
            ->success()
            ->message('Success in Getting Departments.')
            ->data($departments)
            ->logName('Get Departments')
            ->logDesc("Total results: {$departments->count()}")
            ->generate();
    }

    // create department
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'department_name' => 'string|
                unique:ref_departments,department_name,NULL,department_id,deleted_at,NULL|
                required',
            'department_code' => 'string|nullable',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Department Reference', 
                'Create Department', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        RefDepartment::create($request->all());

        $this->logActivity(
            'Department Reference', 
            'Create Department', 
            'Success in Creating Department.'
        );

        return customResponse()
            ->message('Success in Creating Department.')
            ->success(201)
            ->generate();
    }

    // update department
    public function update(Request $request, $departmentCode)
    {
        // resource validation
        $department = RefDepartment::find($departmentCode);

        if ($department === null) {
            $this->logActivity(
                'Department Reference',
                'Update Department',
                'Department not found.'
            );

            return customResponse()
                ->message('Department not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'department_name' => 'string|required',
            'department_code' => 'string|nullable',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Department Reference', 
                'Update Department', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $department->update($request->all());

        $department->updated_by = Auth::user()->user_id;
        $department->save();

        $this->logActivity(
            'Department Reference', 
            'Update Department', 
            'Success in Updating Department.
        ');

        return customResponse()
            ->message('Success in Updating Department.')
            ->success()
            ->generate();
    }

    // delete department
    public function delete(Request $request, $departmentCode)
    {
        // resource validation
        $department = RefDepartment::find($departmentCode);

        if ($department === null) {
            $this->logActivity(
                'Department Reference',
                'Delete Department',
                'Department not found.'
            );

            return customResponse()
                ->message('Department not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $department->deleted_by = Auth::user()->user_id;
        $department->save();

        $department->delete();

        $this->logActivity(
            'Department Reference',
            'Delete Department',
            'Success in Deleting Department.'
        );

        return customResponse()
            ->message('Success in Deleting Department')
            ->success()
            ->generate();
    }

    // get department
    public function show(Request $request, $departmentCode)
    {
        // resource validation
        $department = RefDepartment::find($departmentCode);

        if ($department === null) {
            $this->logActivity(
                'Department Reference',
                'Get Department',
                'Department not found.'
            );

            return customResponse()
                ->message('Department not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $department = RefDepartment::with(
            'office',
            'units', 
            'units.sub_units'
        )->find($departmentCode);

        $this->logActivity(
            'Department Reference',
            'Get Department',
            'Success in Getting Department.'
        );

        return customResponse()
            ->message('Success in Getting Department.')
            ->data($department)
            ->success()
            ->generate();
    }
}
