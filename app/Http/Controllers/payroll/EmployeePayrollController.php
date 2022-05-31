<?php

namespace App\Http\Controllers\payroll;

use App\Http\Controllers\Controller;
use App\Models\EmployeePayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeePayrollController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            //
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }
        $payrollNumber = $this->generateRegularPayrollNo();

        $payroll = Payroll::create([
            'pay_period' => $request->input('pay_period'),
            'period_covered' => $request->input('period_covered'),
            'payroll_type_id' => $request->input('payroll_type_id'),
            'department_id' => $request->input('department_id'),
            'payroll_no' => $payrollNumber,
        ]);

        return customResponse()
            ->data(Payroll::find($payroll->payroll_id))
            ->message('Payroll has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $employeePayroll = EmployeePayroll::find($id);
        if ($employeePayroll) {
            $employeePayroll->delete();
            return customResponse()
                ->data($employeePayroll)
                ->message('Employee Payroll successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Employee Payroll not found.')
            ->notFound()
            ->generate();
    }

    public function generateEmployeePayrollNo($id)
    {
        return EmployeePayroll::where('payroll_id', $id)
            ->whereNull('deleted_at')
            ->get()
            ->count() + 1;
    }
}
