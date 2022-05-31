<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\EmployeeLoanRule;

class EmployeeLoanRuleController extends Controller
{
    // get employee loan rules
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
        $employeeLoanRules = EmployeeLoanRule::
            whereNested(function ($query) use ($search) {
                $query->where('emp_loan_rule_desc', 'LIKE', $search)
                    ->orWhere('min_monthly_salary_for_getting_loan', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Employee Loan Rule API', 
            'View Employee Loan Rules', 
            'Success in Getting Employee Loan Rules.'
        );

        return customResponse()
            ->message('Success in Getting Employee Loan Rules.')
            ->data($employeeLoanRules)
            ->success()
            ->generate();
    }

    // create employee loan rule
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'emp_loan_rule_desc' => 'string|unique:tbl_employee_loan_rules,emp_loan_rule_desc|required',
            'min_monthly_salary_for_getting_loan' => 'numeric|gte:0|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Employee Loan Rule API', 
                'Create Employee Loan Rule', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // if activated, deactivate activated
        if ($request->input('activated') == true) {
            EmployeeLoanRule::where('activated', true)->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);
        }
        
        // create
        $employeeLoanRule = EmployeeLoanRule::create($request->all());

        $employeeLoanRule->created_by = Auth::user()->user_id;
        $employeeLoanRule->save();

        $this->logActivity(
            'Employee Loan Rule API', 
            'Create Employee Loan Rule', 
            'Success in Creating Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Creating Employee Loan Rule.')
            ->success(201)
            ->generate();
    }

    // update employee loan rule
    public function update(Request $request, $elrId)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::find($elrId);

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Update Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'emp_loan_rule_desc' => "string|
                unique:tbl_employee_loan_rules,emp_loan_rule_desc,{$elrId},emp_loan_rule_id|
                required",
            'min_monthly_salary_for_getting_loan' => 'numeric|gte:0|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Employee Loan Rule API', 
                'Update Employee Loan Rule', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // if activated, deactivate activated other than itself
        if ($request->input('activated') == true) {
            EmployeeLoanRule::where('activated', true)
                ->where('emp_loan_rule_id', '!=', $elrId)
                ->update([
                    'activated' => false,
                    'updated_by' => Auth::user()->user_id
                ]);
        }

        // Update
        $employeeLoanRule->update($request->all());

        $employeeLoanRule->updated_by = Auth::user()->user_id;
        $employeeLoanRule->save();

        $this->logActivity(
            'Employee Loan Rule API', 
            'Update Employee Loan Rule', 
            'Success in Updating Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Updating Employee Loan Rule.')
            ->success()
            ->generate();
    }

    // delete employee loan rule
    public function destroy(Request $request, $elrId)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::find($elrId);

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Delete Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $employeeLoanRule->deleted_by = Auth::user()->user_id;
        $employeeLoanRule->save();

        $employeeLoanRule->delete();

        $this->logActivity(
            'Employee Loan Rule API',
            'Delete Employee Loan Rule',
            'Success in Deleting Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Deleting Employee Loan Rule')
            ->success()
            ->generate();
    }

    // get employee loan rule
    public function show(Request $request, $elrId)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::find($elrId);

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Get Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Employee Loan Rule API',
            'Get Employee Loan Rule',
            'Success in Getting Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Getting Employee Loan Rule.')
            ->data($employeeLoanRule)
            ->success()
            ->generate();
    }

    // activate employee loan rule
    public function activate(Request $request, $elrId)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::find($elrId);

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Activate Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->success(404)
                ->generate();
        }

        // if activated, deactivate activated other than itself
        EmployeeLoanRule::where('activated', true)
            ->where('emp_loan_rule_id', '!=', $elrId)
            ->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);

        // activate
        $employeeLoanRule->update(['activated' => true]);

        $employeeLoanRule->updated_by = Auth::user()->user_id;
        $employeeLoanRule->save();

        $this->logActivity(
            'Employee Loan Rule API',
            'Activate Employee Loan Rule',
            'Success in Activating Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Activating Employee Loan Rule.')
            ->success()
            ->generate();
    }
    
    // deactivate employee loan rule
    public function deactivate(Request $request, $elrId)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::find($elrId);

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Deactivate Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $employeeLoanRule->update(['activated' => false]);

        $employeeLoanRule->updated_by = Auth::user()->user_id;
        $employeeLoanRule->save();

        $this->logActivity(
            'Employee Loan Rule API',
            'Deactivate Employee Loan Rule',
            'Success in Deactivating Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Deactivating Employee Loan Rule.')
            ->success()
            ->generate();
    }

    // get active employee loan rule
    public function getActive(Request $request)
    {
        // resource validation
        $employeeLoanRule = EmployeeLoanRule::where('activated', true)->first();

        if ($employeeLoanRule === null) {
            $this->logActivity(
                'Employee Loan Rule API',
                'Get Active Employee Loan Rule',
                'Employee Loan Rule not found.'
            );

            return customResponse()
                ->message('Employee Loan Rule not found.')
                ->success(404)
                ->generate();
        }
        
        // get

        $this->logActivity(
            'Employee Loan Rule API',
            'Get Active Employee Loan Rule',
            'Success in Getting Active Employee Loan Rule.'
        );

        return customResponse()
            ->message('Success in Getting Active Employee Loan Rule.')
            ->data($employeeLoanRule)
            ->success()
            ->generate();
    }
}
