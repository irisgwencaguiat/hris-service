<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\EmployeeNoPayLoan;
use App\Models\EmployeeLoan;

class EmployeeNoPayLoanController extends Controller
{
    // get employee no pay loans
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
            'employee_id' => 'employee_id'
        ], [], 'STRICT');
        
        // get data
        $employeeNoPayLoans = EmployeeNoPayLoan::
            where($filters)
            ->whereNested(function ($query) use ($search) {
                $query->where('period_start', 'LIKE', $search)
                    ->orWhere('period_end', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Employee No Pay Loan API', 
            'View Employee No Pay Loan Taxes', 
            'Success in Getting Employee No Pay Loans.'
        );

        return customResponse()
            ->message('Success in Getting Employee No Pay Loans.')
            ->data($employeeNoPayLoans)
            ->success()
            ->generate();
    }

    // create employee no pay loan
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'employee_loan_id' => 'exists:tbl_employee_loans,employee_loan_id,deleted_at,NULL|required',
            'period_start' => 'date|required',
            'period_end' => 'date|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Employee No Pay Loan API', 
                'Create Employee No Pay Loan', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // create
        $request->request->add(['employee_id' => EmployeeLoan::find($request->input('employee_loan_id'))->employee_id]);
        $employeeNoPayLoan = EmployeeNoPayLoan::create($request->all());

        $employeeNoPayLoan->created_by = Auth::user()->user_id;
        $employeeNoPayLoan->save();

        $this->logActivity(
            'Employee No Pay Loan API', 
            'Create Employee No Pay Loan', 
            'Success in Creating Employee No Pay Loan.'
        );

        return customResponse()
            ->message('Success in Creating Employee No Pay Loan.')
            ->success(201)
            ->generate();
    }

    // update employee no pay loan
    public function update(Request $request, $employeeNoPayLoanId)
    {
        // resource validation
        $employeeNoPayLoan = EmployeeNoPayLoan::find($employeeNoPayLoanId);

        if ($employeeNoPayLoan === null) {
            $this->logActivity(
                'Employee No Pay Loan API',
                'Update Employee No Pay Loan',
                'Employee No Pay Loan not found.'
            );

            return customResponse()
                ->message('Employee No Pay Loan not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'employee_loan_id' => 'exists:tbl_employee_loans,employee_loan_id,deleted_at,NULL|required',
            'period_start' => 'date|required',
            'period_end' => 'date|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Employee No Pay Loan API', 
                'Update Employee No Pay Loan', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $request->request->add(['employee_id' => EmployeeLoan::find($request->input('employee_loan_id'))->employee_id]);
        $employeeNoPayLoan->update($request->all());

        $employeeNoPayLoan->updated_by = Auth::user()->user_id;
        $employeeNoPayLoan->save();

        $this->logActivity(
            'Employee No Pay Loan API', 
            'Update Employee No Pay Loan', 
            'Success in Updating Employee No Pay Loan.'
        );

        return customResponse()
            ->message('Success in Updating Employee No Pay Loan.')
            ->success()
            ->generate();
    }

    // delete employee no pay loan
    public function destroy(Request $request, $employeeNoPayLoanId)
    {
        // resource validation
        $employeeNoPayLoan = EmployeeNoPayLoan::find($employeeNoPayLoanId);

        if ($employeeNoPayLoan === null) {
            $this->logActivity(
                'Employee No Pay Loan API',
                'Delete Employee No Pay Loan',
                'Employee No Pay Loan not found.'
            );

            return customResponse()
                ->message('Employee No Pay Loan not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $employeeNoPayLoan->deleted_by = Auth::user()->user_id;
        $employeeNoPayLoan->save();

        $employeeNoPayLoan->delete();

        $this->logActivity(
            'Employee No Pay Loan API',
            'Delete Employee No Pay Loan',
            'Success in Deleting Employee No Pay Loan.'
        );

        return customResponse()
            ->message('Success in Deleting Employee No Pay Loan')
            ->success()
            ->generate();
    }

    // get employee no pay loan
    public function show(Request $request, $employeeNoPayLoanId)
    {
        // resource validation
        $employeeNoPayLoan = EmployeeNoPayLoan::find($employeeNoPayLoanId);

        if ($employeeNoPayLoan === null) {
            $this->logActivity(
                'Employee No Pay Loan API',
                'Get Employee No Pay Loan',
                'Employee No Pay Loan not found.'
            );

            return customResponse()
                ->message('Employee No Pay Loan not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Employee No Pay Loan API',
            'Get Employee No Pay Loan',
            'Success in Getting Employee No Pay Loan.'
        );

        return customResponse()
            ->message('Success in Getting Employee No Pay Loan.')
            ->data($employeeNoPayLoan)
            ->success()
            ->generate();
    }
}
