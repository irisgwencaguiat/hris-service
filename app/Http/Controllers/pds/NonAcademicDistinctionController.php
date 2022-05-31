<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsNonAcademicDistinction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NonAcademicDistinctionController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'distinction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsNonAcademicDistinction = PdsNonAcademicDistinction::create([
            'employee_id' => $id,
            'distinction' => $request->input('distinction'),
        ]);

        return customResponse()
            ->data(PdsNonAcademicDistinction::find($pdsNonAcademicDistinction->pds_non_academic_distinction_id))
            ->message('PDS Non Academic Distinction has been created.')
            ->success()
            ->generate();
    }


    public function show($id)
    {
        $pdsNonAcademicDistinction = PdsNonAcademicDistinction::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsNonAcademicDistinction)
            ->message('PDS Non Academic Distinction successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'distinction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }


        $pdsNonAdademicDistinction = PdsNonAcademicDistinction::updateOrCreate(
            ['pds_non_academic_distinction_id' => $id],
            [
                'distinction' => $request->input('distinction'),
            ]);


        return customResponse()
            ->data(PdsNonAcademicDistinction::find($pdsNonAdademicDistinction->pds_non_academic_distinction_id))
            ->message('PDS Non Academic Distinction has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsNonAcademicDistinction = PdsNonAcademicDistinction::find($id);
        if ($pdsNonAcademicDistinction) {
            $pdsNonAcademicDistinction->delete();
            return customResponse()
                ->data($pdsNonAcademicDistinction)
                ->message('PDS Non Academic Distinction successfully deleted.')
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
