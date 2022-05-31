<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeRequirementController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'requirement_id' => 'required',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $employeeRequirement = EmployeeRequirement::create([
            'employee_id' => $id,
            'requirement_id' => $request->input('requirement_id'),
            'note' => $request->input('note'),
        ]);

        return customResponse()
            ->data(
                EmployeeRequirement::with(['requirement'])->find(
                    $employeeRequirement->employee_requirement_id
                )
            )
            ->message('Employee Requirement has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $employeeRequirements = EmployeeRequirement::with(['requirement'])
            ->where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($employeeRequirements)
            ->message('Employee Requirements successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $employeeRequirement = EmployeeRequirement::updateOrCreate(
            ['employee_requirement_id' => $id],
            [
                'requirement_id' => $request->input('requirement_id'),
                'note' => $request->input('note'),
            ]
        );

        return customResponse()
            ->data(
                EmployeeRequirement::with(['requirement'])->find(
                    $employeeRequirement->employee_requirement_id
                )
            )
            ->message('Employee Requirement has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $employeeRequirement = EmployeeRequirement::find($id);
        if ($employeeRequirement) {
            $employeeRequirement->delete();
            return customResponse()
                ->data($employeeRequirement)
                ->message('Employee Requirement successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Employee Requirement not found.')
            ->notFound()
            ->generate();
    }
}
