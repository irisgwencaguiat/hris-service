<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnGovernmentServiceRelative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GovernmentServiceRelativeController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'relative_name' => 'string|nullable',
            'relationship' => 'string|nullable',
            'position' => 'string|nullable',
            'agency_office_name' => 'string|nullable',
            'agency_office_address' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $governmentServiceRelative = SalnGovernmentServiceRelative::create([
            'employee_id' => $id,
            'relative_name' => $request->input('relative_name'),
            'relationship' => $request->input('relationship'),
            'position' => $request->input('position'),
            'agency_office_name' => $request->input('agency_office_name'),
            'agency_office_address' => $request->input('agency_office_address'),
        ]);

        return customResponse()
            ->data(
                SalnGovernmentServiceRelative::find(
                    $governmentServiceRelative->saln_government_service_relative_id
                )
            )
            ->message('Saln Relative in the Government has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $governmentServiceRelative = SalnGovernmentServiceRelative::where(
            'employee_id',
            $id
        )
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($governmentServiceRelative)
            ->message('Saln Relatives in the Government successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $governmentServiceRelative = SalnGovernmentServiceRelative::updateOrCreate(
            ['saln_government_service_relative_id' => $id],
            [
                'relative_name' => $request->input('relative_name'),
                'relationship' => $request->input('relationship'),
                'position' => $request->input('position'),
                'agency_office_name' => $request->input('agency_office_name'),
                'agency_office_address' => $request->input(
                    'agency_office_address'
                ),
            ]
        );

        return customResponse()
            ->data(
                SalnGovernmentServiceRelative::find(
                    $governmentServiceRelative->saln_government_service_relative_id
                )
            )
            ->message('Saln Relative in the Government has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $governmentServiceRelative = SalnGovernmentServiceRelative::find($id);
        if ($governmentServiceRelative) {
            $governmentServiceRelative->delete();
            return customResponse()
                ->data($governmentServiceRelative)
                ->message(
                    'Saln Relative in the Government successfully deleted.'
                )
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Relative in the Government not found.')
            ->notFound()
            ->generate();
    }
}
