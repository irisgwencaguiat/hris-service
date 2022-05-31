<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsSkillsAndHobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillsAndHobbyController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'skill' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsSkillsAndHobby = PdsSkillsAndHobby::create([
            'employee_id' => $id,
            'skill' => $request->input('skill'),
        ]);

        return customResponse()
            ->data(
                PdsSkillsAndHobby::find(
                    $pdsSkillsAndHobby->pds_skills_and_hobby_id
                )
            )
            ->message('PDS Skills and Hobby has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $pdsSkillsAndHobby = PdsSkillsAndHobby::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($pdsSkillsAndHobby)
            ->message('PDS Skills and Hobbies successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'skill' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsSkillsAndHobby = PdsSkillsAndHobby::updateOrCreate(
            ['pds_skills_and_hobby_id' => $id],
            [
                'skill' => $request->input('skill'),
            ]
        );

        return customResponse()
            ->data(
                PdsSkillsAndHobby::find(
                    $pdsSkillsAndHobby->pds_skills_and_hobby_id
                )
            )
            ->message('PDS Skills and Hobby has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsSkillsandHobby = PdsSkillsAndHobby::find($id);
        if ($pdsSkillsandHobby) {
            $pdsSkillsandHobby->delete();
            return customResponse()
                ->data($pdsSkillsandHobby)
                ->message('PDS Skills and Hobby successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Skills and Hobby  not found.')
            ->notFound()
            ->generate();
    }
}
