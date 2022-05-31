<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsWorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkExperienceController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsWorkExperience = PdsWorkExperience::create([
            'employee_id' => $id,
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'position' => $request->input('position'),
            'department' => $request->input('department'),
            'monthly_salary' => (double) $request->input('monthly_salary'),
            'salary_grade' => $request->input('salary_grade'),
            'employment_status' => $request->input('employment_status'),
            'govt_service' => (boolean) $request->input('govt_service'),
        ]);

        return customResponse()
            ->data(PdsWorkExperience::find($pdsWorkExperience->pds_work_experience_id))
            ->message('PDS Work Experience has been created.')
            ->success()
            ->generate();
    }


    public function show($id)
    {
        $pdsWorkExperience = PdsWorkExperience::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsWorkExperience)
            ->message('PDS Work Experience successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $pdsWorkExperience = PdsWorkExperience::updateOrCreate(
            ['pds_work_experience_id' => $id],
            [
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'position' => $request->input('position'),
            'department' => $request->input('department'),
            'monthly_salary' => $request->input('monthly_salary'),
            'salary_grade' => $request->input('salary_grade'),
            'employment_status' => $request->input('employment_status'),
            'govt_service' => $request->input('govt_service'),
        ]);


        return customResponse()
            ->data(PdsWorkExperience::find($pdsWorkExperience->pds_work_experience_id))
            ->message('PDS Work Experience has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsWorkExperience = PdsWorkExperience::find($id);
        if ($pdsWorkExperience) {
            $pdsWorkExperience->delete();
            return customResponse()
                ->data($pdsWorkExperience)
                ->message('PDS Work Experience successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Work Experience not found.')
            ->notFound()
            ->generate();
    }
}
