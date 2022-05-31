<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollAccountInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollAccountInfoController extends Controller
{
    public function show($id)
    {
        $payrollAccountInfo = PayrollAccountInfo::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get()
            ->first();

        return customResponse()
            ->data($payrollAccountInfo)
            ->message('Payroll Account Info successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $payrollAccountInfo = PayrollAccountInfo::updateOrCreate(
            ['employee_id' => $id],
            [
                'issuing_bank' => $request->input('issuing_bank'),
                'account_number' => $request->input('account_number'),
                'card_type' => $request->input('card_type'),
            ]
        );

        return customResponse()
            ->data(
                PayrollAccountInfo::find(
                    $payrollAccountInfo->payroll_account_info_id
                )
            )
            ->message('Payroll Account Info has been updated.')
            ->success()
            ->generate();
    }
}
