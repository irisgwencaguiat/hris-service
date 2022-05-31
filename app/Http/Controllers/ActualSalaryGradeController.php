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
use App\Models\ActualSalaryGrade;
use App\Models\SalaryGradeVersion;

class ActualSalaryGradeController extends Controller
{
    // get actual salary grades
    public function index(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        if (SalaryGradeVersion::find($salaryGradeVersionId) == null) {
            $this->logActivity(
                'Actual Salary Grade Transaction',
                'Get Actual Salary Grades',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_at' => 'ASC']
        );
        
        // get data
        $actualSalaryGrades = ActualSalaryGrade::
            where('salary_grade_version_id', $salaryGradeVersionId)
            ->whereNested(function ($query) use ($search) {
                $query->where('salary_grade_id', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Actual Salary Grade Transaction', 
            'View Actual Salary Grades', 
            'Success in Getting Actual Salary Grades.'
        );

        return customResponse()
            ->message('Success in Getting Actual Salary Grades.')
            ->data($actualSalaryGrades)
            ->success()
            ->generate();
    }

    // create actual salary grade
    public function store(Request $request, $salaryGradeVersionId)
    {
        // resource validation
        if (SalaryGradeVersion::find($salaryGradeVersionId) == null) {
            $this->logActivity(
                'Actual Salary Grade Transaction',
                'Get Actual Salary Grades',
                'Salary Grade Version not found.'
            );

            return customResponse()
                ->message('Salary Grade Version not found.')
                ->success(404)
                ->generate();
        }

        // request validation
        $validator = Validator::make($request->all(), [
            'salary_grade_id' => 'exists:ref_salary_grades,salary_grade_id,deleted_at,NULL|required',
            'step_1' => 'numeric|required',
            'step_2' => 'numeric|required',
            'step_3' => 'numeric|required',
            'step_4' => 'numeric|required',
            'step_5' => 'numeric|required',
            'step_6' => 'numeric|required',
            'step_7' => 'numeric|required',
            'step_8' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Actual Salary Grade Transaction', 
                'Create Actual Salary Grade', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        $request->request->add(['salary_grade_version_id' => $salaryGradeVersionId]);
        ActualSalaryGrade::create($request->all());

        $this->logActivity(
            'Actual Salary Grade Transaction', 
            'Create Actual Salary Grade', 
            'Success in Creating Actual Salary Grade.'
        );

        return customResponse()
            ->message('Success in Creating Actual Salary Grade.')
            ->success(201)
            ->generate();
    }

    // update actual salary grade
    public function update(Request $request, $salaryGradeVersionId, $actualSalaryGradeId)
    {
        // resource validation
        $actualSalaryGrade = ActualSalaryGrade::find($actualSalaryGradeId);

        if ($actualSalaryGrade === null) {
            $this->logActivity(
                'Actual Salary Grade Transaction',
                'Update Actual Salary Grade',
                'Actual Salary Grade not found.'
            );

            return customResponse()
                ->message('Actual Salary Grade not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'salary_grade_id' => 'exists:ref_salary_grades,salary_grade_id,deleted_at,NULL|required',
            'step_1' => 'numeric|required',
            'step_2' => 'numeric|required',
            'step_3' => 'numeric|required',
            'step_4' => 'numeric|required',
            'step_5' => 'numeric|required',
            'step_6' => 'numeric|required',
            'step_7' => 'numeric|required',
            'step_8' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Actual Salary Grade Transaction', 
                'Update Actual Salary Grade', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $actualSalaryGrade->update($request->all());

        $actualSalaryGrade->updated_by = Auth::user()->user_id;
        $actualSalaryGrade->save();

        $this->logActivity(
            'Actual Salary Grade Transaction', 
            'Update Actual Salary Grade', 
            'Success in Updating Actual Salary Grade.
        ');

        return customResponse()
            ->message('Success in Updating Actual Salary Grade.')
            ->success()
            ->generate();
    }

    // delete actual salary grade
    public function delete(Request $request, $salaryGradeVersionId, $actualSalaryGradeId)
    {
        // resource validation
        $actualSalaryGrade = ActualSalaryGrade::find($actualSalaryGradeId);

        if ($actualSalaryGrade === null) {
            $this->logActivity(
                'Actual Salary Grade Transaction',
                'Delete Actual Salary Grade',
                'Actual Salary Grade not found.'
            );

            return customResponse()
                ->message('Actual Salary Grade not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $actualSalaryGrade->deleted_by = Auth::user()->user_id;
        $actualSalaryGrade->save();

        $actualSalaryGrade->delete();

        $this->logActivity(
            'Actual Salary Grade Transaction',
            'Delete Actual Salary Grade',
            'Success in Deleting Actual Salary Grade.'
        );

        return customResponse()
            ->message('Success in Deleting Actual Salary Grade')
            ->success()
            ->generate();
    }

    // get actual salary grade
    public function show(Request $request, $salaryGradeVersionId, $actualSalaryGradeId)
    {
        // resource validation
        $actualSalaryGrade = ActualSalaryGrade::find($actualSalaryGradeId);

        if ($actualSalaryGrade === null) {
            $this->logActivity(
                'Actual Salary Grade Transaction',
                'Get Actual Salary Grade',
                'Actual Salary Grade not found.'
            );

            return customResponse()
                ->message('Actual Salary Grade not found.')
                ->failed(404)
                ->generate();
        }

        // return
        $this->logActivity(
            'Actual Salary Grade Transaction',
            'Get Actual Salary Grade',
            'Success in Getting Actual Salary Grade.'
        );

        return customResponse()
            ->message('Success in Getting Actual Salary Grade.')
            ->data($actualSalaryGrade)
            ->success()
            ->generate();
    }
}
