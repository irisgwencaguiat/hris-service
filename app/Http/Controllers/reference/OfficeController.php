<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\RefOffice;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Employee;
use App\Models\EmployeeAppointmentHistory;
use App\Models\EmployeeProfile;
use App\Models\EmployeeReport;
use App\Models\EmployeeWorkingHour;
use App\Models\PayrollAccountInfo;
use App\Models\PdsFamilyBackground;
use App\Models\PdsPersonalInfo;
use App\Models\PreviousPlantilla;
use App\Models\PreviousPosition;
use App\Models\User;
use App\Models\UserProfile;

class OfficeController extends Controller
{
    // get offices
    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->generate();

        // get data
        $offices = RefOffice::usingAPIParam($apiParam);
        
        return customResponse()
            ->success() 
            ->message('Success in Getting Offices.')
            ->data($offices)
            ->logName('Get Offices')
            ->logDesc("Total results: {$offices->count()}")
            ->generate();
    }

    // create office
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'office_name' => 'string|unique:ref_offices,office_name|required',
            'office_code' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Office Reference', 
                'Create Office', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        RefOffice::create($request->all());

        $this->logActivity(
            'Office Reference', 
            'Create Office', 
            'Success in Creating Office.'
        );

        return customResponse()
            ->message('Success in Creating Office.')
            ->success(201)
            ->generate();
    }

    // update office
    public function update(Request $request, $officeCode)
    {
        // resource validation
        $office = RefOffice::find($officeCode);

        if ($office === null) {
            $this->logActivity(
                'Office Reference',
                'Update Office',
                'Office not found.'
            );

            return customResponse()
                ->message('Office not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'office_name' => "string|
                unique:ref_offices,office_name,{$officeCode},office_id|
                required",
            'office_code' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Office Reference', 
                'Update Office', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $office->update($request->all());

        $office->updated_by = Auth::user()->user_id;
        $office->save();

        $this->logActivity(
            'Office Reference', 
            'Update Office', 
            'Success in Updating Office.
        ');

        return customResponse()
            ->message('Success in Updating Office.')
            ->success()
            ->generate();
    }

    // delete office
    public function delete(Request $request, $officeCode)
    {
        // resource validation
        $office = RefOffice::find($officeCode);

        if ($office === null) {
            $this->logActivity(
                'Office Reference',
                'Delete Office',
                'Office not found.'
            );

            return customResponse()
                ->message('Office not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $office->deleted_by = Auth::user()->user_id;
        $office->save();

        $office->delete();

        $this->logActivity(
            'Office Reference',
            'Delete Office',
            'Success in Deleting Office.'
        );

        return customResponse()
            ->message('Success in Deleting Office')
            ->success()
            ->generate();
    }

    // get office
    public function show(Request $request, $officeCode)
    {
        // resource validation
        $office = RefOffice::find($officeCode);

        if ($office === null) {
            $this->logActivity(
                'Office Reference',
                'Get Office',
                'Office not found.'
            );

            return customResponse()
                ->message('Office not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $office = RefOffice::with(
            'departments', 
            'departments.units', 
            'departments.units.sub_units'
        )->find($officeCode);

        $this->logActivity(
            'Office Reference',
            'Get Office',
            'Success in Getting Office.'
        );

        return customResponse()
            ->message('Success in Getting Office.')
            ->data($office)
            ->success()
            ->generate();
    }

    public function exportEmployees(Request $request)
    {
        // // Office Details
        $office = (object) RefOffice::where('office_id', $request->input('office_id'))->get();

        $apiParam = apiParam()
            ->request($request)
            ->filterables(['office_id', 'department_id'])
            ->filterColumns([
                'office_id' => 'tbl_employees.office_id',
                'department_id' => 'tbl_employees.department_id',
            ])
            ->generate();

        // Employees in office
        $employees = Employee::with([
                    'profile',
                    'department',
                    'employmentStatus',
                    'appointmentNature',
                    'unit',
                    'stepIncrement',
                    'plantilla',
                    'position',
                    'salaryGrade',
                    'office',
                    'designation',
                ])
                ->leftJoin(
                    'ref_offices',
                    'ref_offices.office_id',
                    'tbl_employees.office_id'
                )
                ->leftJoin(
                    'ref_departments',
                    'ref_departments.department_id',
                    'tbl_employees.department_id'
                )
                ->searchExtras([
                    'ref_offices.office_name',
                    'ref_departments.department_name',
                ])
                ->whereNull('tbl_employees.deleted_at')
                ->where($apiParam->filters)
                ->select('tbl_employees.*')
                ->get();
        
        // Get authenticated user details
        $authenticatedUser = UserProfile::where('user_profile_id', Auth::user()->user_id)->get();
        
        //redirect output to client browser
        header('Access-Control-Allow-Origin: ' . config('app.app_allow_origins'));
        header('Access-Control-Allow-Credentials: true');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Merge Cell
        $spreadsheet->getActiveSheet()->mergeCells('A1:D1');
        $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
        $spreadsheet->getActiveSheet()->mergeCells('A4:D4');

        // Styles
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');

        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getStyle('B')->getAlignment()->setHorizontal('center');

        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getStyle('C')->getAlignment()->setHorizontal('center');

        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('D')->getAlignment()->setHorizontal('center');

        // Column Width
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        // For the header
        $sheet->setCellValue('A1', 'MALABON CITY');
        $sheet->setCellValue('A4', 'EMPLOYEE LIST');

        // Loop to get office details
        foreach($office AS $o) {
            $sheet->setCellValue('A2', $o->office_name);
        }

        // Set time
        $date = Carbon::now();
        $sheet->setCellValue('D6','As of: '.$date->format('F d Y g:i A'));

        $sheet->setCellValue('A6', 'PREPARED BY:');
        // Loop to get the authenticated user details
        foreach($authenticatedUser AS $auth) {
            $sheet->setCellValue('B6', $auth->first_name. ' ' .$auth->last_name);
        }
        $sheet->setCellValue('B7','NAME OF EMPLOYEE');

        if($apiParam->filters == null) {
            // For the header of the row
            $sheet->setCellValue('A9', 'NO.');
            $sheet->setCellValue('B9', 'EMPLOYEE NUMBER');
            $sheet->setCellValue('C9', 'NAME');
            $sheet->setCellValue('D9', 'OFFICE');
            $sheet->setCellValue('E9', 'DEPARTMENT');
            $sheet->setCellValue('F9', 'POSITION');

            // Fetching Data
            $column = 10;
            $no = 1;

            foreach($employees as $emp) {
                $sheet->setCellValue('A'.$column, $no);
                $sheet->setCellValue('B'.$column, $emp->employee_no);
                $sheet->setCellValue('C'.$column, $emp->first_name . ' ' . $emp->last_name);
                $sheet->setCellValue('D'.$column, $emp->office->office_name);
                $sheet->setCellValue('E'.$column, $emp->department->department_name);
                $sheet->setCellValue('F'.$column, $emp->position->position_name);
                $column++;
                $no++;
            }
        }   
        else{
            //For the header of the row
            $sheet->setCellValue('A9', 'NO.');
            $sheet->setCellValue('B9', 'EMPLOYEE NUMBER');
            $sheet->setCellValue('C9', 'NAME');
            $sheet->setCellValue('D9', 'POSITION');

            // Fetching Data
            $column = 10;
            $no = 1;

            foreach($employees as $emp) {
                $sheet->setCellValue('A'.$column, $no);
                $sheet->setCellValue('B'.$column, $emp->employee_no);
                $sheet->setCellValue('C'.$column, $emp->first_name . ' ' . $emp->last_name);
                $sheet->setCellValue('D'.$column, $emp->position->position_name);
                $column++;
                $no++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        // redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
