<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Database\QueryException;
use App\Models\EmployeeLoan;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeMonthlySalary;
use App\Models\Employee;

class EmployeeLoanController extends Controller
{
    // get employee loans
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
        $employeeLoans = EmployeeLoan::
            with(
                'employee',
                'loanType',
                'deductions'
            )
            ->where($filters)
            ->whereNested(function ($query) use ($search) {
                $query->where('loan_type_id', 'LIKE', $search)
                    ->orWhere('amount', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Employee Loan API', 
            'View Employee Loan Taxes', 
            'Success in Getting Employee Loans.'
        );

        return customResponse()
            ->message('Success in Getting Employee Loans.')
            ->data($employeeLoans)
            ->success()
            ->generate();
    }

    // create employee loan
    public function store(Request $request)
    {   
        DB::beginTransaction();
        try {
            // request validation
            $validator = Validator::make($request->all(), [
                'employee_id' => 'exists:tbl_employees,employee_id,deleted_at,NULL|required',
                'loan_type_id' => 'exists:ref_loan_types,loan_type_id,deleted_at,NULL|required',
                'amount' => 'numeric|gte:0|required',
                'period_start' => 'date|required',
                'period_end' => 'date|required'
            ]);

            if ($validator->fails()) {
                DB::rollback();

                $this->logActivity(
                    'Employee Loan API', 
                    'Create Employee Loan', 
                    'Please fill out the fields properly.'
                );

                return customResponse()
                    ->message('Please fill out the fields properly.')
                    ->failed()
                    ->errors($validator->errors())
                    ->generate();
            }

            // create
            $employeeLoan = EmployeeLoan::create($request->all());

            $employeeLoan->created_by = Auth::user()->user_id;
            $employeeLoan->save();

            // add deductions
            $this->addDeductions([
                'periodStart' => $request->input('period_start'),
                'periodEnd' => $request->input('period_end'),
                'amount' => $request->input('amount'),
                'employeeId' => $request->input('employee_id'),
                'employeeLoan' => $employeeLoan
            ]);

            $this->logActivity(
                'Employee Loan API', 
                'Create Employee Loan', 
                'Success in Creating Employee Loan.'
            );

            DB::commit();

            return customResponse()
                ->message('Success in Creating Employee Loan.')
                ->success(201)
                ->generate();

        } catch (QueryException $e) {
            DB::rollback();

            return customResponse()
                ->message('Failed in Creating Employee Loan.')
                ->success(500)
                ->generate();
        }
    }

    // update employee loan
    public function update(Request $request, $employeeLoanId)
    {
        DB::beginTransaction();
        try {
            // resource validation
            $employeeLoan = EmployeeLoan::find($employeeLoanId);

            if ($employeeLoan === null) {
                DB::rollback();

                $this->logActivity(
                    'Employee Loan API',
                    'Update Employee Loan',
                    'Employee Loan not found.'
                );

                return customResponse()
                    ->message('Employee Loan not found.')
                    ->success(404)
                    ->generate();
            }

            // request validation   
            $validator = Validator::make($request->all(), [
                'employee_id' => 'exists:tbl_employees,employee_id,deleted_at,NULL|required',
                'loan_type_id' => 'exists:ref_loan_types,loan_type_id,deleted_at,NULL|required',
                'amount' => 'numeric|gte:0|required',
                'period_start' => 'date|required',
                'period_end' => 'date|required'
            ]);

            if ($validator->fails()) {
                DB::rollback();

                $this->logActivity(
                    'Employee Loan API', 
                    'Update Employee Loan', 
                    'Please fill out the fields properly.'
                );

                return customResponse()
                    ->message('Please fill out the fields properly.')
                    ->failed()
                    ->errors($validator->errors())
                    ->generate();
            }

            // get old period start and end
            $oldPeriodStart = $employeeLoan->period_start;
            $oldPeriodEnd = $employeeLoan->period_end;

            // update
            $employeeLoan->update($request->all());

            $employeeLoan->updated_by = Auth::user()->user_id;
            $employeeLoan->save();

            // remove deductions for the loan
            $this->removeDeductions([
                'employeeLoan' => $employeeLoan,
                'oldPeriodStart' => $oldPeriodStart,
                'oldPeriodEnd' => $oldPeriodEnd,
                'employeeId' => $request->input('employee_id')
            ]);

            // add deductions
            $this->addDeductions([
                'periodStart' => $request->input('period_start'),
                'periodEnd' => $request->input('period_end'),
                'amount' => $request->input('amount'),
                'employeeId' => $request->input('employee_id'),
                'employeeLoan' => $employeeLoan
            ]);

            DB::commit();

            $this->logActivity(
                'Employee Loan API', 
                'Update Employee Loan', 
                'Success in Updating Employee Loan.'
            );

            return customResponse()
                ->message('Success in Updating Employee Loan.')
                ->success()
                ->generate();

        } catch (QueryException $e) {
            DB::rollback();

            return customResponse()
                ->message('Failed in Creating Employee Loan.')
                ->success(500)
                ->generate();
        }
    }

    // delete employee loan
    public function destroy(Request $request, $employeeLoanId)
    {
        // resource validation
        $employeeLoan = EmployeeLoan::find($employeeLoanId);

        if ($employeeLoan === null) {
            $this->logActivity(
                'Employee Loan API',
                'Delete Employee Loan',
                'Employee Loan not found.'
            );

            return customResponse()
                ->message('Employee Loan not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $employeeLoan->deleted_by = Auth::user()->user_id;
        $employeeLoan->save();

        $employeeLoan->delete();

        $this->logActivity(
            'Employee Loan API',
            'Delete Employee Loan',
            'Success in Deleting Employee Loan.'
        );

        return customResponse()
            ->message('Success in Deleting Employee Loan')
            ->success()
            ->generate();
    }

    // get employee loan
    public function show(Request $request, $employeeLoanId)
    {
        // resource validation
        $employeeLoan = EmployeeLoan::find($employeeLoanId);

        if ($employeeLoan === null) {
            $this->logActivity(
                'Employee Loan API',
                'Get Employee Loan',
                'Employee Loan not found.'
            );

            return customResponse()
                ->message('Employee Loan not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $employeeLoan = EmployeeLoan::with(
            'employee',
            'loanType',
            'deductions'
        )->find($employeeLoanId);

        $this->logActivity(
            'Employee Loan API',
            'Get Employee Loan',
            'Success in Getting Employee Loan.'
        );

        return customResponse()
            ->message('Success in Getting Employee Loan.')
            ->data($employeeLoan)
            ->success()
            ->generate();
    }

    private function addDeductions(Array $vars)
    {  
        if (count($vars) == 0) {
            return false;
        }

        extract($vars);

        $periodStart = new DateTime($periodStart);
        $periodEnd = new DateTime($periodEnd);
        $start = clone($periodStart);
        $start->modify('first day of this month');
        $end = clone($periodEnd);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $amount = doubleval($amount);
        $salary = Employee::find($employeeId)->salary();

        foreach ($period as $dt) {
            $deduction = 0;
            $deduction15 = 0;
            $deduction30 = 0;

            $day15 = clone($dt);
            $day15->add(new DateInterval('P14D'));
            $day30 = clone($dt);
            $day30->add(new DateInterval('P29D'));

            // 15th day
            if ($day15 >= $periodStart && $day15 <= $periodEnd) {
                EmployeeDeduction::create([
                    'employee_loan_id' => $employeeLoan->employee_loan_id,
                    'employee_id' => $employeeId,
                    'day' => 15,
                    'deduction_date' => $day15->format('Y-m-d'),
                    'amount' => $amount,
                    'created_by' => Auth::user()->user_id
                ]);
                
                $deduction += $amount;
                $deduction15 += $amount;
            }

            // 30th day
            if ($day30->format('n') != '2' && 
                $day30 >= $periodStart && 
                $day30 <= $periodEnd
            ) {
                EmployeeDeduction::create([
                    'employee_loan_id' => $employeeLoan->employee_loan_id,
                    'employee_id' => $employeeId,
                    'day' => 30,
                    'deduction_date' => $day30->format('Y-m-d'),
                    'amount' => $amount,
                    'created_by' => Auth::user()->user_id
                ]);
                
                $deduction += $amount;
                $deduction30 += $amount;
            }

            // add monthly salaries
            $empMonthlySalary = EmployeeMonthlySalary::where([
                'employee_id' => $employeeId,
                'year' => intval($dt->format('Y')),
                'month' => intval($dt->format('m')),
            ])->first();

            if ($empMonthlySalary == null) {
                $empMonthlySalary = EmployeeMonthlySalary::create([
                    'employee_id' => $employeeId,
                    'year' => intval($dt->format('Y')),
                    'month' => intval($dt->format('m')),
                    'net_salary' => $salary,
                    'net_salary_15th' => ($salary / 2),
                    'net_salary_30th' => ($salary / 2),
                    'created_by' => Auth::user()->user_id
                ]);
            }

            $empMonthlySalary->net_salary = 
                doubleval($empMonthlySalary->net_salary) 
                + doubleval($empMonthlySalary->loan_deduction)
                - $deduction;

            $empMonthlySalary->net_salary_15th = 
                doubleval($empMonthlySalary->net_salary_15th) 
                + doubleval($empMonthlySalary->loan_deduction_15th)
                - $deduction15;

            $empMonthlySalary->net_salary_30th = 
                doubleval($empMonthlySalary->net_salary_30th) 
                + doubleval($empMonthlySalary->loan_deduction_30th)
                - $deduction30;

            $empMonthlySalary->loan_deduction = $deduction;
            $empMonthlySalary->loan_deduction_15th = $deduction15;
            $empMonthlySalary->loan_deduction_30th = $deduction30;

            $empMonthlySalary->updated_by = Auth::user()->user_id;
            $empMonthlySalary->save();
        }
    }

    private function removeDeductions(Array $vars)
    {
        if (count($vars) == 0) {
            return false;
        }

        extract($vars);

        $employeeDeductions = EmployeeDeduction::where(
            'employee_loan_id', $employeeLoan->employee_loan_id
        )->get();

        foreach ($employeeDeductions as $employeeDeduction) {
            $employeeDeduction->deleted_by = Auth::user()->user_id;
            $employeeDeduction->save();
            $employeeDeduction->delete();
        }
        
        // reset back the salary
        $periodStart = new DateTime($oldPeriodStart);
        $periodEnd = new DateTime($oldPeriodEnd);
        $start = clone($periodStart);
        $start->modify('first day of this month');
        $end = clone($periodEnd);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $empMonthlySalary = EmployeeMonthlySalary::where([
                'employee_id' => $employeeId,
                'year' => intval($dt->format('Y')),
                'month' => intval($dt->format('m')),
            ])->first();

            $empMonthlySalary->net_salary = 
                doubleval($empMonthlySalary->net_salary) 
                + doubleval($empMonthlySalary->loan_deduction);

            $empMonthlySalary->net_salary_15th = 
                doubleval($empMonthlySalary->net_salary_15th) 
                + doubleval($empMonthlySalary->loan_deduction_15th);

            $empMonthlySalary->net_salary_30th = 
                doubleval($empMonthlySalary->net_salary_30th) 
                + doubleval($empMonthlySalary->loan_deduction_30th);

            $empMonthlySalary->loan_deduction = 0;
            $empMonthlySalary->loan_deduction_15th = 0;
            $empMonthlySalary->loan_deduction_30th = 0;

            $empMonthlySalary->updated_by = Auth::user()->user_id;
            $empMonthlySalary->save();
        }
    }
}
