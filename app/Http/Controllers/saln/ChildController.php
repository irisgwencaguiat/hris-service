<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ChildController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        if (Carbon::parse($request->input('date_of_birth'))->age > 17) {
            return customResponse()
                ->data(null)
                ->message('Child age 18 and above is not valid.')
                ->failed()
                ->generate();
        }

        $child = SalnChild::create([
            'employee_id' => $id,
            'date_of_birth' => $request->input('date_of_birth'),
            'age' => Carbon::parse($request->input('date_of_birth'))->age,
        ]);

        return customResponse()
            ->data(SalnChild::find($child->saln_child_id))
            ->message('Saln Child has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $child = SalnChild::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($child)
            ->message('Saln Children successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'date',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        if (Carbon::parse($request->input('date_of_birth'))->age > 17) {
            return customResponse()
                ->data(null)
                ->message('Child age 18 and above is not valid.')
                ->failed()
                ->generate();
        }

        $child = SalnChild::updateOrCreate(
            ['saln_child_id' => $id],
            [
                'date_of_birth' => $request->input('date_of_birth'),
                'age' => Carbon::parse($request->input('date_of_birth'))->age,
            ]
        );

        return customResponse()
            ->data(SalnChild::find($child->saln_child_id))
            ->message('Saln Child has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $child = SalnChild::find($id);
        if ($child) {
            $child->delete();
            return customResponse()
                ->data($child)
                ->message('Saln Child successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Child not found.')
            ->notFound()
            ->generate();
    }
}
