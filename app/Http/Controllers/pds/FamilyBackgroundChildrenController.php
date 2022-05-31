<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsFamilybackgroundChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilyBackgroundChildrenController extends Controller
{

    public function show($id)
    {
        $pdsFamilyBackgroundChildren = PdsFamilybackgroundChild::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsFamilyBackgroundChildren)
            ->message('PDS Family Background Child successfully found.')
            ->success()
            ->generate();
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'child_first_name' => 'required|string',
            'child_last_name' => 'required|string',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }
        $pdsFamilyBackgroundChildren = PdsFamilybackgroundChild::create(
            [
                'employee_id' => $id,
                'child_last_name' => $request->input('child_last_name'),
                'child_middle_name' => $request->input('child_middle_name'),
                'child_first_name' => $request->input('child_first_name'),
                'child_suffix' => $request->input('child_suffix'),
                'date_of_birth' => $request->input('date_of_birth'),
            ]
        );
        return customResponse()
            ->data(PdsFamilybackgroundChild::find($pdsFamilyBackgroundChildren->pds_familybackground_child_id))
            ->message('PDS Family Background Child has been created.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $pdsFamilyBackgroundChildren = PdsFamilybackgroundChild::updateOrCreate(
            ['pds_familybackground_child_id' => $id],
            [
                'child_last_name' => $request->input('child_last_name'),
                'child_middle_name' => $request->input('child_middle_name'),
                'child_first_name' => $request->input('child_first_name'),
                'child_suffix' => $request->input('child_suffix'),
                'date_of_birth' => $request->input('date_of_birth'),
            ]
        );

        return customResponse()
            ->data(PdsFamilybackgroundChild::find($pdsFamilyBackgroundChildren->pds_familybackground_child_id))
            ->message('PDS Family Background Child has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsFamilyBackgroundChild = PdsFamilybackgroundChild::find($id);
        if ($pdsFamilyBackgroundChild) {
            $pdsFamilyBackgroundChild->delete();
            return customResponse()
                ->data($pdsFamilyBackgroundChild)
                ->message('PDS Family Background Child successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Family Background Child not found.')
            ->notFound()
            ->generate();
    }
}
