<?php

namespace App\Http\Controllers\payroll;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\WorkDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayTaggingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'holiday_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $holiday = Holiday::create([
            'date' => $request->input('date'),
            'holiday_type_id' => $request->input('holiday_type_id'),
            'description' => $request->input('description'),
        ]);

        return customResponse()
            ->data(Holiday::find($holiday->holiday_id))
            ->message('Holiday has been created.')
            ->success()
            ->generate();
    }

    public function show($year)
    {
        $holidays = Holiday::whereYear('date', $year)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($holidays)
            ->message('Holidays successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::updateOrCreate(
            ['holiday_id' => $id],
            [
                'date' => $request->input('date'),
                'holiday_type_id' => $request->input('holiday_type_id'),
                'description' => $request->input('description'),
            ]
        );

        return customResponse()
            ->data(Holiday::find($holiday->holiday_id))
            ->message('Holiday has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $holiday = Holiday::find($id);
        if ($holiday) {
            $holiday->delete();
            return customResponse()
                ->data($holiday)
                ->message('Holiday successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Holiday not found.')
            ->notFound()
            ->generate();
    }
}
