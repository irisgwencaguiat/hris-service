<?php

namespace App\Http\Controllers\payroll;

use App\Http\Controllers\Controller;
use App\Models\WorkDay;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkDayController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        for ($m = 1; $m <= 12; $m++) {
            $month = DateTime::createFromFormat('!m', $m)->format('F');
            WorkDay::create([
                'year' => $request->input('year'),
                'month' => $month,
                'work_days' => $this->getWeekdays($m, $request->input('year')),
            ]);
        }

        return customResponse()
            ->data(WorkDay::where('year', $request->input('year'))->get())
            ->message('Work Days has been created.')
            ->success()
            ->generate();
    }

    public function show($year)
    {
        $workDays = WorkDay::where('year', $year)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($workDays)
            ->message('Work Days successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'work_days' => 'required|',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $workDay = WorkDay::updateOrCreate(
            ['work_day_id' => $id],
            [
                'work_days' => $request->input('work_days'),
                'edit_no' => WorkDay::find($id)['edit_no'] + 1,
            ]
        );

        return customResponse()
            ->data(WorkDay::find($workDay->work_day_id))
            ->message('Work Day has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $workDay = WorkDay::find($id);
        if ($workDay) {
            $workDay->delete();
            return customResponse()
                ->data($workDay)
                ->message('Work Day successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Work Day not found.')
            ->notFound()
            ->generate();
    }

    public function getWeekdays($m, $y)
    {
        $lastDay = date('t', mktime(0, 0, 0, $m, 1, $y));
        $weekdays = 0;
        for ($d = 29; $d <= $lastDay; $d++) {
            $wd = date('w', mktime(0, 0, 0, $m, $d, $y));
            if ($wd > 0 && $wd < 6) {
                $weekdays++;
            }
        }
        return $weekdays + 20;
    }
}
