<?php

namespace App\Http\Controllers\payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $payrolls = new Payroll();

        $pay_period = $request->get('pay_period');
        $period_covered = $request->get('period_covered');
        $department = $request->get('department');

        echo $department;
        if ($department === 'all') {
            $payrolls = $payrolls
                ->with(['department', 'refPayrollType'])
                ->where('period_covered', $period_covered)
                ->where('pay_period', $pay_period)
                ->whereNull('deleted_at')
                ->latest()
                ->paginate(
                    (int) $request->get('per_page', 10),
                    ['*'],
                    'page',
                    (int) $request->get('page', 1)
                );
        } else {
            $payrolls = $payrolls
                ->with(['department', 'refPayrollType'])
                ->where('period_covered', $period_covered)
                ->where('pay_period', $pay_period)
                ->whereHas('department', function ($query) use ($department) {
                    $query->where('department_id', $department);
                })
                ->whereNull('deleted_at')
                ->latest()
                ->paginate(
                    (int) $request->get('per_page', 10),
                    ['*'],
                    'page',
                    (int) $request->get('page', 1)
                );
        }
        return customResponse()
            ->data($payrolls)
            ->message('Getting payrolls is successful.')
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pay_period' => 'required|string',
            'period_covered' => 'required|string',
            'payroll_type_id' => 'required',
            'department_id' => 'required',
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
        $payroll = Payroll::find($id);

        return customResponse()
            ->data($payroll)
            ->message('Holidays successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::updateOrCreate(
            ['payroll_id' => $id],
            [
                'pay_period' => $request->input('pay_period'),
                'period_covered' => $request->input('period_covered'),
                'payroll_type_id' => $request->input('payroll_type_id'),
                'department_id' => $request->input('department_id'),
            ]
        );

        return customResponse()
            ->data(Payroll::find($payroll->payroll_id))
            ->message('Payroll has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $payroll = Payroll::find($id);
        if ($payroll) {
            $payroll->delete();
            return customResponse()
                ->data($payroll)
                ->message('Payroll successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Payroll not found.')
            ->notFound()
            ->generate();
    }

    public function generateRegularPayrollNo()
    {
        $count = Payroll::get()->count() + 1;

        return 'RP' .
            date('Y') % 100 .
            '-' .
            str_pad($count, 5, '0', STR_PAD_LEFT) .
            '-00';
    }
}
