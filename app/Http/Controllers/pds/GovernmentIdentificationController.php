<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsGovernmentIdentification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GovernmentIdentificationController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'issued_id' => 'required|string',
            'id_no' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsGovernmentIdentification = PdsGovernmentIdentification::create([
            'employee_id' => $id,
            'issued_id' => $request->input('issued_id'),
            'id_no' => $request->input('id_no'),
            'issuance_date' => $request->input('issuance_date'),
            'issuance_place' => $request->input('issuance_place'),
        ]);

        return customResponse()
            ->data(
                PdsGovernmentIdentification::find(
                    $pdsGovernmentIdentification->pds_government_identification_id
                )
            )
            ->message('PDS Government Id has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $pdsGovernmentIdentifications = PdsGovernmentIdentification::where(
            'employee_id',
            $id
        )
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($pdsGovernmentIdentifications)
            ->message('PDS Government Ids successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsGovernmentIdentification = PdsGovernmentIdentification::updateOrCreate(
            ['pds_government_identification_id' => $id],
            [
                'issued_id' => $request->input('issued_id'),
                'id_no' => $request->input('id_no'),
                'issuance_date' => $request->input('issuance_date'),
                'issuance_place' => $request->input('issuance_place'),
            ]
        );

        return customResponse()
            ->data(
                PdsGovernmentIdentification::find(
                    $pdsGovernmentIdentification->pds_government_identification_id
                )
            )
            ->message('PDS Government Id has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsGovernmentIdentification = PdsGovernmentIdentification::find($id);
        if ($pdsGovernmentIdentification) {
            $pdsGovernmentIdentification->delete();
            return customResponse()
                ->data($pdsGovernmentIdentification)
                ->message('PDS Government Id successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Government Id not found.')
            ->notFound()
            ->generate();
    }
}
