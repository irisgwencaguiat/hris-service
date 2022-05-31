<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsFamilyBackground;
use Illuminate\Http\Request;

class FamilyBackgroundController extends Controller
{

    public function show($id)
    {
        $pdsFamilyBackground = PdsFamilyBackground::where('employee_id', (int) $id)->get()->first();

        return customResponse()
            ->data($pdsFamilyBackground)
            ->message('Employee successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $pdsFamilyBackground = PdsFamilyBackground::updateOrCreate(
            ['employee_id' => $id],
            [
                'spouse_last_name' => $request->input('spouse_last_name'),
                'spouse_middle_name' => $request->input('spouse_middle_name'),
                'spouse_first_name' => $request->input('spouse_first_name'),
                'spouse_suffix' => $request->input('spouse_suffix'),
                'spouse_occupation' => $request->input('spouse_occupation'),
                'spouse_employer' => $request->input('spouse_employer'),
                'spouse_business_address' => $request->input('spouse_business_address'),
                'spouse_telephone_no' => $request->input('spouse_telephone_no'),
                'father_last_name' => $request->input('father_last_name'),
                'father_middle_name' => $request->input('father_middle_name'),
                'father_first_name' => $request->input('father_first_name'),
                'father_suffix' => $request->input('father_suffix'),
                'mother_maiden_name' => $request->input('mother_maiden_name'),
                'mother_last_name' => $request->input('mother_last_name'),
                'mother_middle_name' => $request->input('mother_middle_name'),
                'mother_first_name' => $request->input('mother_first_name'),
                'mother_suffix' => $request->input('mother_suffix'),
            ]
        );

        return customResponse()
            ->data(PdsFamilyBackground::find($pdsFamilyBackground->pds_family_background_id))
            ->message('PDS Family Background has been updated.')
            ->success()
            ->generate();
    }


    public function destroy($id)
    {
        //
    }
}
