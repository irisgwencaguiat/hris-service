<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsVoluntaryWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoluntaryWorkController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required|string',
            'organization_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsVoluntaryWork = PdsVoluntaryWork::create([
            'employee_id' => $id,
            'organization_name' => $request->input('organization_name'),
            'organization_address' => $request->input('organization_address'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'no_of_hours' => (float) $request->input('no_of_hours'),
            'position' => $request->input('position'),
        ]);

        return customResponse()
            ->data(
                PdsVoluntaryWork::find($pdsVoluntaryWork->pds_voluntary_work_id)
            )
            ->message('PDS Voluntary Work has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $pdsVoluntaryWork = PdsVoluntaryWork::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($pdsVoluntaryWork)
            ->message('PDS Voluntary Work successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsVoluntaryWork = PdsVoluntaryWork::updateOrCreate(
            ['pds_voluntary_work_id' => $id],
            [
                'organization_name' => $request->input('organization_name'),
                'organization_address' => $request->input(
                    'organization_address'
                ),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'no_of_hours' => (float) $request->input('no_of_hours'),
                'position' => $request->input('position'),
            ]
        );

        return customResponse()
            ->data(
                PdsVoluntaryWork::find($pdsVoluntaryWork->pds_voluntary_work_id)
            )
            ->message('PDS Voluntary Work has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsVoluntaryWork = PdsVoluntaryWork::find($id);
        if ($pdsVoluntaryWork) {
            $pdsVoluntaryWork->delete();
            return customResponse()
                ->data($pdsVoluntaryWork)
                ->message('PDS Voluntary Work successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Voluntary Work not found.')
            ->notFound()
            ->generate();
    }
}
