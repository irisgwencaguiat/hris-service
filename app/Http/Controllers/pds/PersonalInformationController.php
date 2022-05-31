<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsPersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalInformationController extends Controller
{
    public function show($id)
    {
        $pdsPersonalInfo = PdsPersonalInfo::where('employee_id', (int) $id)
            ->get()
            ->first();

        return customResponse()
            ->data($pdsPersonalInfo)
            ->message('Employee successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsPersonalInfo = PdsPersonalInfo::updateOrCreate(
            ['employee_id' => $id],
            [
                'gsis_no' => $request->input('gsis_no'),
                'bp_no' => $request->input('bp_no'),
                'crn_umid_no' => $request->input('crn_umid_no'),
                'pagibig_no' => $request->input('pagibig_no'),
                'philhealth_no' => $request->input('philhealth_no'),
                'sss_no' => $request->input('sss_no'),
                'tin_no' => $request->input('tin_no'),
                'agency_employee_no' => $request->input('agency_employee_no'),
                'telephone_no' => $request->input('telephone_no'),
                'mobile_no' => $request->input('mobile_no'),
            ]
        );

        return customResponse()
            ->data(
                PdsPersonalInfo::find($pdsPersonalInfo->pds_personal_info_id)
            )
            ->message('PDS Personal Information has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        //
    }
}
