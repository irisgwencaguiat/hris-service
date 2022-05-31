<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsLearningDevelopment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearningDevelopmentController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsLearningDevelopment = PdsLearningDevelopment::create([
            'employee_id' => $id,
            'title' => $request->input('title'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'no_of_hours' => (double) $request->input('no_of_hours'),
            'learning_development_type' => $request->input('learning_development_type'),
            'conducted_by' => $request->input('conducted_by')
        ]);

        return customResponse()
            ->data(PdsLearningDevelopment::find($pdsLearningDevelopment->pds_learning_development_id))
            ->message('PDS Learning and Development has been created.')
            ->success()
            ->generate();
    }


    public function show($id)
    {
        $pdsLearningDevelopment = PdsLearningDevelopment::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsLearningDevelopment)
            ->message('PDS Learning and Development successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $pdsLearningDevelopment = PdsLearningDevelopment::updateOrCreate(
            ['pds_learning_development_id' => $id],
            [
                'title' => $request->input('title'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'no_of_hours' => (double) $request->input('no_of_hours'),
                'learning_development_type' => $request->input('learning_development_type'),
                'conducted_by' => $request->input('conducted_by')
            ]);


        return customResponse()
            ->data(PdsLearningDevelopment::find($pdsLearningDevelopment->pds_learning_development_id))
            ->message('PDS Learning and Development has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsLearningDevelopment = PdsLearningDevelopment::find($id);
        if ($pdsLearningDevelopment) {
            $pdsLearningDevelopment->delete();
            return customResponse()
                ->data($pdsLearningDevelopment)
                ->message('PDS Learning and Development successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Learning and Development not found.')
            ->notFound()
            ->generate();
    }
}
