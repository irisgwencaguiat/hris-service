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
use App\Models\References\SalaryGrade;

class SalaryGradeController extends Controller
{
    // get salary grades
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 50)), 50, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['CONVERT(salary_grade_name, SIGNED)' => 'ASC']
        );
        
        // get data
        $versions = SalaryGrade::
            whereNested(function ($query) use ($search) {
                $query->where('salary_grade_name', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy(DB::raw($column), $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Salary Grade Reference', 
            'View Salary Grades', 
            'Success in Getting Salary Grades.'
        );

        return customResponse()
            ->message('Success in Getting Salary Grades.')
            ->data($versions)
            ->success()
            ->generate();
    }

    // create salary grade
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'salary_grade_name' => 'unique:ref_salary_grades,salary_grade_name|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Salary Grade Reference', 
                'Create Salary Grade', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        SalaryGrade::create($request->all());

        $this->logActivity(
            'Salary Grade Reference', 
            'Create Salary Grade', 
            'Success in Creating Salary Grade.'
        );

        return customResponse()
            ->message('Success in Creating Salary Grade.')
            ->success(201)
            ->generate();
    }

    // update salary grade
    public function update(Request $request, $salaryGradeId)
    {
        // resource validation
        $salaryGrade = SalaryGrade::find($salaryGradeId);

        if ($salaryGrade === null) {
            $this->logActivity(
                'Salary Grade Reference',
                'Update Salary Grade',
                'Salary Grade not found.'
            );

            return customResponse()
                ->message('Salary Grade not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'salary_grade_name' => 'unique:ref_salary_grades,salary_grade_id|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Salary Grade Reference', 
                'Update Salary Grade', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $salaryGrade->update($request->all());

        $salaryGrade->updated_by = Auth::user()->user_id;
        $salaryGrade->save();

        $this->logActivity(
            'Salary Grade Reference', 
            'Update Salary Grade', 
            'Success in Updating Salary Grade.
        ');

        return customResponse()
            ->message('Success in Updating Salary Grade.')
            ->success()
            ->generate();
    }

    // delete salary grade
    public function delete(Request $request, $salaryGradeId)
    {
        // resource validation
        $salaryGrade = SalaryGrade::find($salaryGradeId);

        if ($salaryGrade === null) {
            $this->logActivity(
                'Salary Grade Reference',
                'Delete Salary Grade',
                'Salary Grade not found.'
            );

            return customResponse()
                ->message('Salary Grade not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $salaryGrade->deleted_by = Auth::user()->user_id;
        $salaryGrade->save();

        $salaryGrade->delete();

        $this->logActivity(
            'Salary Grade Reference',
            'Delete Salary Grade',
            'Success in Deleting Salary Grade.'
        );

        return customResponse()
            ->message('Success in Deleting Salary Grade')
            ->success()
            ->generate();
    }

    // get salary grade
    public function show(Request $request, $salaryGradeId)
    {
        // resource validation
        $salaryGrade = SalaryGrade::find($salaryGradeId);

        if ($salaryGrade === null) {
            $this->logActivity(
                'Salary Grade Reference',
                'Get Salary Grade',
                'Salary Grade not found.'
            );

            return customResponse()
                ->message('Salary Grade not found.')
                ->failed(404)
                ->generate();
        }

        // return
        $this->logActivity(
            'Salary Grade Reference',
            'Get Salary Grade',
            'Success in Getting Salary Grade.'
        );

        return customResponse()
            ->message('Success in Getting Salary Grade.')
            ->data($salaryGrade)
            ->success()
            ->generate();
    }
}
