<?php

namespace App\Http\Controllers;

use App\Models\EmployeeWorkingHour;
use Illuminate\Http\Request;

class EmployeeWorkingHourController extends Controller
{
    public function show($id)
    {
        $employeeWorkingHours = EmployeeWorkingHour::where(
            'employee_id',
            (int) $id
        )->get();

        return customResponse()
            ->data($employeeWorkingHours)
            ->message('Employee Working Hours successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $employeeWorkingHour = EmployeeWorkingHour::find((int) $id);
        if ($employeeWorkingHour) {
            $employeeWorkingHour->update([
                'time_in' => $request->input('time_in'),
                'time_out' => $request->input('time_out'),
                'is_off' => filter_var(
                    $request->input('is_off'),
                    FILTER_VALIDATE_BOOLEAN
                ),
                'is_flexible' => filter_var(
                    $request->input('is_flexible'),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ]);
            return customResponse()
                ->data($employeeWorkingHour)
                ->message('Employee Working Hour successfully updated.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Employee Working Hour not found.')
            ->notFound()
            ->generate();
    }
}
