<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsOtherInfoQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherInfoQuestionController extends Controller
{
    public function show($id)
    {
        $pdsOtherInfoQuestion = PdsOtherInfoQuestion::where('employee_id', $id)->whereNull('deleted_at')->get()->first();

        return customResponse()
            ->data($pdsOtherInfoQuestion)
            ->message('PDS Other Info Questions successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $pdsOtherInfoQuestion = PdsOtherInfoQuestion::updateOrCreate(
            ['employee_id' => $id],
            [
            'question_34a' => (boolean) $request->input('question_34a'),
            'question_34b' => (boolean) $request->input('question_34b'),
            'question_34b_details' => $request->input('question_34b_details'),
            'question_35a' => (boolean) $request->input('question_35a'),
            'question_35a_details' => $request->input('question_35a_details'),
            'question_35b' => (boolean) $request->input('question_35b'),
            'question_35b_datefiled' => $request->input('question_35b_datefiled'),
            'question_35b_casestatus' => $request->input('question_35b_casestatus'),
            'question_36' => (boolean) $request->input('question_36'),
            'question_36_details' => $request->input('question_36_details'),
            'question_37' => (boolean) $request->input('question_37'),
            'question_37_details' => $request->input('question_37_details'),
            'question_38a' => (boolean) $request->input('question_38a'),
            'question_38a_details' => $request->input('question_38a_details'),
            'question_38b' => (boolean) $request->input('question_38b'),
            'question_38b_details' => $request->input('question_38b_details'),
            'question_39' => (boolean) $request->input('question_39'),
            'question_39_details' => $request->input('question_39_details'),
            'question_40a' => (boolean) $request->input('question_40a'),
            'question_40a_details' => $request->input('question_40a_details'),
            'question_40b' => (boolean) $request->input('question_40b'),
            'question_40b_idno' => $request->input('question_40b_idno'),
            'question_40c' => (boolean) $request->input('question_40c'),
            'question_40c_idno' => $request->input('question_40c_idno'),
        ]);

        return customResponse()
            ->data(PdsOtherInfoQuestion::find($pdsOtherInfoQuestion->pds_other_info_question_id))
            ->message('PDS Other Info Questions has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {

    }
}
