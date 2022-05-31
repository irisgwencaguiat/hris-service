<?php

namespace App\Http\Controllers;

use App\Models\Dtr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use DB;
use Carbon\Carbon;
use Auth;

class DtrController extends Controller
{
    /** Show employee DTR for display in Kiosk */
    public function show($employeeId)
    {
        $dtr = Dtr::where('employee_id', $employeeId)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return customResponse()
            ->data($dtr)
            ->message('Success in getting DTR')
            ->success()
            ->generate();     
    }

    /** Show Employee DTR in table */
    public function showDtrTable(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 1000)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';
    
        // get data
        $dtrs = Dtr::where('employee_id', $request->input('employee_id'))
                    // ->whereMonth('created_at', $request->input('month'))
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);

        $this->logActivity(
            'DTR', 
            'View Employee DTR', 
            'Success in Getting DTR.'
        );

        return customResponse()
            ->message('Success in Getting DTR.')
            ->data($dtrs)
            ->success()
            ->generate();
    }

    /** Store employee DTR */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'kiosk_id' => 'required',
                'remarks' => 'required',
            ]);

            if($validator->fails()) {
                return customResponse()
                    ->message('Please fill out the fields properly.')
                    ->failed()
                    ->errors($validator->errors())
                    ->generate();
            }

            /** Store */
            $dtr = Dtr::create($request->all());

            return customResponse()
                    ->message('Recorded Successfully.')
                    ->success()
                    ->generate();

        }catch(QueryException $e){
            DB::rollback();
            return customResponse()
                ->message('Failed in Storing Data')
                ->failed()
                ->generate();
        }
    }

    /** Admin store DTR */
    public function adminStoreDtr(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'date' => 'required',
                'time' => 'required',
                'remarks' => 'required',
            ]);

            if($validator->fails()) {
                return customResponse()
                    ->message('Please fill out the fields properly.')
                    ->failed()
                    ->errors($validator->errors())
                    ->generate();
            }

            $date = $request->input('date') . ' ' .$request->input('time');

            /** Store */
            $dtr = Dtr::create([
                'employee_id' => $request->input('employee_id'),
                'kiosk_id' => 0,
                'log_datetime' => $date,
                'remarks' => $request->input('remarks'),
                'updated_by' => Auth::user()->user_id
            ]);

            return customResponse()
                    ->message('Recorded Successfully.')
                    ->success()
                    ->generate();

        }catch(QueryException $e){
            DB::rollback();
            return customResponse()
                ->message('Failed in Storing Data')
                ->failed()
                ->generate();
        }
    }

    /** Update employee DTR */
    public function update(Request $request, $dtrId)
    {
        // resource validation
        $dtr = Dtr::find($dtrId);

        if($dtr === null) {
            $this->logActivity(
                'DTR',
                'Delete DTR',
                'DTR not found.'
            );

            return customResponse()
                ->message('DTR not found.')
                ->success(404)
                ->generate();
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required',
            'remarks' => 'required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'DTR', 
                'Update DTR', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        $date = $request->input('date') . ' ' .$request->input('time');

        // Update
        $dtr->update([
            'log_datetime' => $date,
            'remarks' => $request->input('remarks'),
            'updated_by' => Auth::user()->user_id
        ]);

        $dtr->save();

        $this->logActivity(
            'DTR', 
            'Update DTR', 
            'Success in Updating DTR.
        ');

        return customResponse()
            ->message('Success in Updating DTR.')
            ->success()
            ->generate();
    }

    /** Delete employee DTR */
    public function delete(Request $request, $dtrId)
    {
        // resource validation
        $dtr = Dtr::find($dtrId);

        if($dtr === null) {
            $this->logActivity(
                'DTR',
                'Delete DTR',
                'DTR not found.'
            );

            return customResponse()
                ->message('DTR not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $dtr->deleted_by = Auth::user()->user_id;
        $dtr->save();

        $dtr->delete();

        $this->logActivity(
            'DTR',
            'Delete DTR',
            'Success in Deleting DTR.'
        );

        return customResponse()
            ->message('Success in Deleting DTR')
            ->success()
            ->generate();
    }

    public function generateDtr(Request $request){
     try {
            DB::beginTransaction();

            // Validation
            $validator = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'employees' => 'required',
            ]);

            if($validator->fails()) {
                return customResponse()
                    ->message('Please fill out the fields properly.')
                    ->failed()
                    ->errors($validator->errors())
                    ->generate();
            }

            // Add schedule dates
            $dateStart = Carbon::parse($request->input('start_date', 0));
            $dateEnd = Carbon::parse($request->input('end_date', 0));
            $timeStart = Carbon::parse($request->input('start_time', 0));
            $timeEnd = Carbon::parse($request->input('end_time', 0));
            $employees = (object) $request->input('employees', 0);
            // period date
            // Create dates
            $date = $dateStart;
            while ($date <= $dateEnd) {
                // Add to Database
                foreach($employees AS $e){
                    Dtr::create([
                        'kiosk_id' => 0,
                        'employee_id' => $e['employee_id'],
                        'remarks' => 'time-in',
                        'log_datetime' =>  substr($date,0,10) . ' ' . substr($timeStart,11),
                        'updated_by' => Auth::user()->user_id
                    ]);
                    Dtr::create([
                        'kiosk_id' => 0,
                        'employee_id' => $e['employee_id'],
                        'remarks' => 'time-out',
                        'log_datetime' => substr($date,0,10) . ' ' . substr($timeEnd,11),
                        'updated_by' => Auth::user()->user_id
                    ]);
               }
                // Add 1 day
                $date->addDay();
            }

            DB::commit();

            
            return customResponse()
                ->message('Success in Generating DTR')
                ->success()
                ->generate();
        }
        catch (QueryException $e) {
            DB::rollback();
            Log::debug($e);
            return customResponse()
                ->message('Error in Generating DTR')
                ->failed()
                ->generate();
        }
    }
}
