<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsCsEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CivilServiceEligibilityController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'license_no' => 'required|string',
            'service' => 'required|string',
            'rating' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsCsEligibility = PdsCsEligibility::create([
            'employee_id' => $id,
            'service' => $request->input('service'),
            'rating' => $request->input('rating'),
            'date_of_exam' => $request->input('date_of_exam'),
            'place_of_exam' => $request->input('place_of_exam'),
            'license_no' => $request->input('license_no'),
            'date' => $request->input('date'),
        ]);

        return customResponse()
            ->data(PdsCsEligibility::find($pdsCsEligibility->pcse_id))
            ->message('PDS CS Eligibility has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $pdsCsEligibility = PdsCsEligibility::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($pdsCsEligibility)
            ->message('PDS CS Eligibility successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsCsEligibility = PdsCsEligibility::updateOrCreate(
            ['pcse_id' => $id],
            [
                'service' => $request->input('service'),
                'rating' => $request->input('rating'),
                'date_of_exam' => $request->input('date_of_exam'),
                'place_of_exam' => $request->input('place_of_exam'),
                'license_no' => $request->input('license_no'),
                'date' => $request->input('date'),
            ]
        );

        return customResponse()
            ->data(PdsCsEligibility::find($pdsCsEligibility->pcse_id))
            ->message('PDS CS Eligibility has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsCsEligibility = PdsCsEligibility::find($id);
        if ($pdsCsEligibility) {
            $pdsCsEligibility->delete();
            return customResponse()
                ->data($pdsCsEligibility)
                ->message('PDS CS Eligibility successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS CS Eligibility not found.')
            ->notFound()
            ->generate();
    }
}
