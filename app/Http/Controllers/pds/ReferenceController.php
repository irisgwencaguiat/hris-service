<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferenceController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsReference = PdsReference::create([
            'employee_id' => $id,
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'suffix' => $request->input('suffix'),
            'address' => $request->input('address'),
            'tel_no' => $request->input('tel_no'),
        ]);

        return customResponse()
            ->data(PdsReference::find($pdsReference->pds_reference_id))
            ->message('PDS Reference has been created.')
            ->success()
            ->generate();
    }


    public function show($id)
    {
        $pdsReference = PdsReference::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsReference)
            ->message('PDS Reference successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $pdsReference = PdsReference::updateOrCreate(
            ['pds_reference_id' => $id],
            [
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'suffix' => $request->input('suffix'),
            'address' => $request->input('address'),
            'tel_no' => $request->input('tel_no'),
        ]);
        return customResponse()
            ->data(PdsReference::find($pdsReference->pds_reference_id))
            ->message('PDS Reference has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsReference = PdsReference::find($id);
        if ($pdsReference) {
            $pdsReference->delete();
            return customResponse()
                ->data($pdsReference)
                ->message('PDS Reference successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Reference not found.')
            ->notFound()
            ->generate();
    }
}
