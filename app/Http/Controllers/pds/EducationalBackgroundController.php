<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsEducationalBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationalBackgroundController extends Controller
{

    public function show($id)
    {
        $pdsEducationalBackground = PdsEducationalBackground::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsEducationalBackground)
            ->message('PDS Educational Background successfully found.')
            ->success()
            ->generate();
    }


    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'education_level_id' => 'required|numeric',
            'school_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsEducationalBackground = PdsEducationalBackground::create([
            'employee_id' => $id,
            'education_level' => (int) $request->input('education_level_id'),
            'school_name' => $request->input('school_name'),
            'degree' => $request->input('degree'),
            'attendance_from' => $request->input('attendance_from'),
            'attendance_to' => $request->input('attendance_to'),
            'units_earned' => $request->input('units_earned'),
            'year_graduated' => $request->input('year_graduated'),
            'honors_received' => $request->input('honors_received'),
        ]);

        return customResponse()
            ->data(PdsEducationalBackground::find($pdsEducationalBackground->pds_educational_background_id))
            ->message('PDS Educational Background has been created.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsEducationalBackground = PdsEducationalBackground::updateOrCreate(
            ['pds_educational_background_id' => $id],
            [
            'education_level' => (int) $request->input('education_level_id'),
            'school_name' => $request->input('school_name'),
            'degree' => $request->input('degree'),
            'attendance_from' => $request->input('attendance_from'),
            'attendance_to' => $request->input('attendance_to'),
            'units_earned' => $request->input('units_earned'),
            'year_graduated' => $request->input('year_graduated'),
            'honors_received' => $request->input('honors_received'),
        ]);

        return customResponse()
            ->data(PdsEducationalBackground::find($pdsEducationalBackground->pds_educational_background_id))
            ->message('PDS Educational Background has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsEducationalBackground = PdsEducationalBackground::find($id);
        if ($pdsEducationalBackground) {
            $pdsEducationalBackground->delete();
            return customResponse()
                ->data($pdsEducationalBackground)
                ->message('PDS Educational Background successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Educational Background not found.')
            ->notFound()
            ->generate();
    }
}
