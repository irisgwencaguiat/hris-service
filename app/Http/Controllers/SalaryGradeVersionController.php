<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\SalaryGradeVersion;

class SalaryGradeVersionController extends Controller
{
    // get salary grade versions
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_at' => 'ASC']
        );
        
        // get data
        $versions = SalaryGradeVersion::
            with('actualSalaryGrades')
            ->whereNested(function ($query) use ($search) {
                $query->where('version_year', 'LIKE', $search)
                    ->orWhere('version_desc', 'LIKE', $search)
                    ->orWhere('activated', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Salary Grade Version Reference', 
            'View Salary Grade Versions', 
            'Success in Getting Salary Grade Versions.'
        );

        return customResponse()
            ->message('Success in Getting Salary Grade Versions.')
            ->data($versions)
            ->success()
            ->generate();
    }

    // create salary grade version
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'version_year' => 'numeric|required',
            'version_desc' => 'string|nullable',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Salary Grade Version Reference', 
                'Create Salary Grade Version', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        $version = SalaryGradeVersion::create($request->all());

        if ($request->input('activated')) {
            $activatedVersions = SalaryGradeVersion::
                where('activated', true)
                ->where('salary_grade_version_id', '!=', $version->salary_grade_version_id)
                ->get();
            
            foreach ($activatedVersions as $activatedVersion) {
                $activatedVersion->activated = false;
                $activatedVersion->update();
            }
        }

        $this->logActivity(
            'Salary Grade Version Reference', 
            'Create Salary Grade Version', 
            'Success in Creating Salary Grade Version.'
        );

        return customResponse()
            ->message('Success in Creating Salary Grade Version.')
            ->success(201)
            ->generate();
    }

    // update salary grade version
    public function update(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::find($salaryGradeVersionId);

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version Reference',
                'Update Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'version_year' => 'numeric|required',
            'version_desc' => 'string|nullable',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Salary Grade Version Reference', 
                'Update Salary Grade Version', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $salaryGradeVersion->update($request->all());

        $salaryGradeVersion->updated_by = Auth::user()->user_id;
        $salaryGradeVersion->save();

        
        if ($request->input('activated')) {
            $activatedVersions = SalaryGradeVersion::
                where('activated', true)
                ->where('salary_grade_version_id', '!=', $salaryGradeVersion->salary_grade_version_id)
                ->get();
            
            foreach ($activatedVersions as $activatedVersion) {
                $activatedVersion->activated = false;
                $activatedVersion->update();
            }
        }

        $this->logActivity(
            'Salary Grade Version Reference', 
            'Update Salary Grade Version', 
            'Success in Updating Salary Grade Version.
        ');

        return customResponse()
            ->message('Success in Updating Salary Grade Version.')
            ->success()
            ->generate();
    }

    // delete salary grade version
    public function delete(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::find($salaryGradeVersionId);

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version Reference',
                'Delete Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $salaryGradeVersion->deleted_by = Auth::user()->user_id;
        $salaryGradeVersion->save();

        $salaryGradeVersion->delete();

        $this->logActivity(
            'Salary Grade Version Reference',
            'Delete Salary Grade Version',
            'Success in Deleting Salary Grade Version.'
        );

        return customResponse()
            ->message('Success in Deleting Salary Grade Version')
            ->success()
            ->generate();
    }

    // get salary grade version
    public function show(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::find($salaryGradeVersionId);

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version Reference',
                'Get Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $salaryGradeVersion = $salaryGradeVersion->with(
            'actualSalaryGrades'
        )->find($salaryGradeVersionId);

        // return
        $this->logActivity(
            'Salary Grade Version Reference',
            'Get Salary Grade Version',
            'Success in Getting Salary Grade Version.'
        );

        return customResponse()
            ->message('Success in Getting Salary Grade Version.')
            ->data($salaryGradeVersion)
            ->success()
            ->generate();
    }

    // activate salary grade version
    public function activate(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::find($salaryGradeVersionId);

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version Reference',
                'Activate Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        
        // Update
        $salaryGradeVersion->activated = true;
        $salaryGradeVersion->activated_by = Auth::user()->user_id;
        $salaryGradeVersion->save();

        $activatedVersions = SalaryGradeVersion::
            where('activated', true)
            ->where('salary_grade_version_id', '!=', $salaryGradeVersionId)
            ->get();
        
        foreach ($activatedVersions as $activatedVersion) {
            $activatedVersion->activated = false;
            $activatedVersion->deactivated_by = Auth::user()->user_id;
            $activatedVersion->update();
        }

        $this->logActivity(
            'Salary Grade Version Reference', 
            'Activate Salary Grade Version', 
            'Success in Activating Salary Grade Version.
        ');

        return customResponse()
            ->message('Success in Activating Salary Grade Version.')
            ->success()
            ->generate();
    }

    // deactivate salary grade version
    public function deactivate(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::find($salaryGradeVersionId);

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version Reference',
                'Deactivate Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        
        // Update
        $salaryGradeVersion->activated = false;
        $salaryGradeVersion->deactivated_by = Auth::user()->user_id;
        $salaryGradeVersion->save();

        $this->logActivity(
            'Salary Grade Version Reference', 
            'Deactivate Salary Grade Version', 
            'Success in Deactivating Salary Grade Version.
        ');

        return customResponse()
            ->message('Success in Deactivating Salary Grade Version.')
            ->success()
            ->generate();
    }

    // get active employee loan rule
    public function getActive(Request $request)
    {
        // resource validation
        $salaryGradeVersion = SalaryGradeVersion::where('activated', true)->first();

        if ($salaryGradeVersion === null) {
            $this->logActivity(
                'Salary Grade Version API',
                'Get Active Salary Grade Version',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }
        
        // get
        $salaryGradeVersion = $salaryGradeVersion->with(
            'actualSalaryGrades'
        )->where('activated', true)->first();

        $this->logActivity(
            'Salary Grade Version API',
            'Get Active Salary Grade Version',
            'Success in Getting Active Salary Grade Version.'
        );

        return customResponse()
            ->message('Success in Getting Active Salary Grade Version.')
            ->data($salaryGradeVersion)
            ->success()
            ->generate();
    }
}
