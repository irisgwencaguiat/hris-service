<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Auth;
use Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->filterables([
                'office_id', 
                'department_id',
                'designation_id'
            ])
            ->filterColumns([
                'office_id' => 'tbl_employees.office_id',
                'department_id' => 'tbl_employees.department_id',
                'designation_id' => 'tbl_employees.designation_id',
            ])
            ->generate();

        // get data
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
            'user'
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
            ->latest()
            ->usingAPIParam($apiParam);

        return customResponse()
            ->success()
            ->message('Getting employees is successful.')
            ->data($employees)
            ->logName('Get Employees')
            ->logDesc("Total results: {$employees->count()}")
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:tbl_employees|string|nullable',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'sex' => 'required|string',
            'date_of_birth' => 'required|date',
            'civil_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $name = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
        ];

        if ($this->isNameUnique($name)) {
            return customResponse()
                ->data(null)
                ->message('Name already exist.')
                ->failed()
                ->generate();
        }

        $employeeNumber = $this->generateEmployeeNumber(
            filter_var(
                $request->input('is_elected_official'),
                FILTER_VALIDATE_BOOLEAN
            )
        );
        $password = Hash::make(
            $this->generatePassword(
                $request->input('first_name'),
                $request->input('middle_name'),
                $request->input('last_name'),
                $request->input('date_of_birth')
            )
        );

        $user = User::create([
            'username' => $employeeNumber,
            'password' => $password,
        ]);

        UserProfile::create([
            'user_id' => $user->user_id,
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'user_type_id' => 2,
            'user_classification_id' => 1,
            'email' => $request->input('email'),
        ]);

        $designationId = $request->input('designation_id')
            ? $request->input('designation_id')
            : 3;

        $employee = Employee::create([
            'employee_no' => $employeeNumber,
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'sex' => $request->input('sex'),
            'user_id' => $user->user_id,
            'department_id' => $request->input('department_id'),
            'plantilla_id' => $request->input('plantilla_id'),
            'office_id' => $request->input('office_id'),
            'employment_status_id' => $request->input('employment_status_id'),
            'appointment_nature_id' => $request->input('appointment_nature_id'),
            'remarks' => $request->input('remarks'),
            'salary_grade_id' => $request->input('salary_grade_id'),
            'unit_id' => $request->input('unit_id'),
            'position_id' => $request->input('position_id'),
            'step_increment_id' => $request->input('step_increment_id'),
            'designation_id' => $designationId,
            'employment_status_start_date' => $request->input(
                'employment_status_start_date'
            ),
            'employment_status_end_date' => $request->input(
                'employment_status_end_date'
            ),
        ]);

        PayrollAccountInfo::create([
            'employee_id' => $employee->employee_id,
        ]);

        if ($request->input('plantilla_id')) {
            EmployeeAppointmentHistory::create([
                'employee_id' => $employee->employee_id,
                'plantilla_id' => $request->input('plantilla_id'),
                'office_id' => $request->input('office_id'),
                'department_id' => $request->input('department_id'),
                'salary_grade_id' => $request->input('salary_grade_id'),
                'position_id' => $request->input('position_id'),
                'unit_id' => $request->input('unit_id'),
                'step_increment_id' => $request->input('step_increment_id'),
                'employment_status_id' => $request->input(
                    'employment_status_id'
                ),
                'employment_status_start_date' => $request->input(
                    'employment_status_start_date'
                ),
                'employment_status_end_date' => $request->input(
                    'employment_status_end_date'
                ),
                'appointment_nature_id' => $request->input(
                    'appointment_nature_id'
                ),
                'remarks' => $request->input('remarks'),
            ]);
        }

        EmployeeReport::create([
            'employee_id' => $employee->employee_id,
            'office_id' => $request->input('office_id'),
            'department_id' => $request->input('department_id'),
            'unit_id' => $request->input('unit_id'),
        ]);

        EmployeeProfile::create([
            'employee_id' => $employee->employee_id,
            'employee_no' => $employeeNumber,
            'email' => $request->input('email'),
            'complete_name' => $this->combineNames(
                $request->input('first_name'),
                $request->input('middle_name'),
                $request->input('last_name'),
                $request->input('suffix')
            ),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'date_of_birth' => $request->input('date_of_birth'),
            'sex' => $request->input('sex'),
            'civil_status' => $request->input('civil_status'),
        ]);

        PdsPersonalInfo::create([
            'employee_id' => $employee->employee_id,
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'date_of_birth' => $request->input('date_of_birth'),
            'sex' => $request->input('sex'),
            'civil_status' => $request->input('civil_status'),
        ]);

        PdsFamilyBackground::create([
            'employee_id' => $employee->employee_id,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Monday',
            'day_slug' => 'monday',
            'time_in' => '8:00:00',
            'time_out' => '17:00:00',
            'is_off' => false,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Tuesday',
            'day_slug' => 'tuesday',
            'time_in' => '8:00:00',
            'time_out' => '17:00:00',
            'is_off' => false,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Wednesday',
            'day_slug' => 'wednesday',
            'time_in' => '8:00:00',
            'time_out' => '17:00:00',
            'is_off' => false,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Thursday',
            'day_slug' => 'thursday',
            'time_in' => '8:00:00',
            'time_out' => '17:00:00',
            'is_off' => false,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Friday',
            'day_slug' => 'friday',
            'time_in' => '8:00:00',
            'time_out' => '17:00:00',
            'is_off' => false,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Saturday',
            'day_slug' => 'saturday',
            'is_off' => true,
            'is_flexible' => false,
        ]);

        EmployeeWorkingHour::create([
            'employee_id' => $employee->employee_id,
            'day' => 'Sunday',
            'day_slug' => 'sunday',
            'is_off' => true,
            'is_flexible' => false,
        ]);

        //        $this->logEvent(Auth::user()->id, 'Added an employee with an id: '.Employee::with('Designation')->latest()->first()->id.'.', trans('responses.status.success'));

        return customResponse()
            ->data(Employee::with('profile')->find($employee->employee_id))
            ->message('Employee has been created.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        if (!Employee::find($id)) {
            return customResponse()
                ->data(null)
                ->message('Employee not found.')
                ->notFound()
                ->generate();
        }

        $validator = Validator::make($request->all(), [
            'email' => 'string|nullable',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'sex' => 'required|string',
            'date_of_birth' => 'required|date',
            'civil_status' => 'required|string',
            'height' => 'numeric|nullable',
            'weight' => 'numeric|nullable',
            'designation_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $name = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
        ];

        if (
            $this->isNameUnique($name) &&
            (int) $this->isNameUnique($name)->employee_id !== (int) $id
        ) {
            return customResponse()
                ->data(null)
                ->message('Name already exist.')
                ->failed()
                ->generate();
        }

        $employee = Employee::updateOrCreate(
            ['employee_id' => $id],
            [
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'suffix' => $request->input('suffix'),
                'sex' => $request->input('sex'),
                'designation_id' => $request->input('designation_id'),
            ]
        );

        //        User::updateOrCreate(
        //            ['user_id' => $employee->user_id],
        //            [
        //                'first_name' => $request->input('first_name'),
        //                'middle_name' => $request->input('middle_name'),
        //                'last_name' => $request->input('last_name'),
        //                'suffix' => $request->input('suffix'),
        //                'username' => $request->input('employee_no')
        //            ]
        //        );

        UserProfile::updateOrCreate(
            ['user_id' => $employee->user_id],
            [
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'suffix' => $request->input('suffix'),
                'user_type_id' => 2,
                'user_classification_id' => 1,
                'email' => $request->input('email'),
            ]
        );

        EmployeeProfile::updateOrCreate(
            ['employee_id' => $id],
            [
                'email' => $request->input('email'),
                'complete_name' => $this->combineNames(
                    $request->input('first_name'),
                    $request->input('middle_name'),
                    $request->input('last_name'),
                    $request->input('suffix')
                ),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'middle_name' => $request->input('middle_name'),
                'suffix' => $request->input('suffix'),
                'sex' => $request->input('sex'),
                'date_of_birth' => $request->input('date_of_birth'),
                'civil_status' => $request->input('civil_status'),
                'height' => $request->input('height'),
                'weight' => $request->input('weight'),
                'blood_type' => $request->input('blood_type'),
                'citizenship_type' => $request->input('citizenship_type'),
                'citizenship_process' => $request->input('citizenship_process'),
                'primary_country' => $request->input('primary_country'),
                'secondary_country' => $request->input('secondary_country'),
                'residential_address_line_1' => $request->input(
                    'residential_address_line_1'
                ),
                'residential_street' => $request->input('residential_street'),
                'residential_village' => $request->input('residential_village'),
                'residential_barangay' => $request->input(
                    'residential_barangay'
                ),
                'residential_city' => $request->input('residential_city'),
                'residential_province' => $request->input(
                    'residential_province'
                ),
                'residential_zip_code' => $request->input(
                    'residential_zip_code'
                ),
                'permanent_address_line_1' => $request->input(
                    'permanent_address_line_1'
                ),
                'permanent_street' => $request->input('permanent_street'),
                'permanent_village' => $request->input('permanent_village'),
                'permanent_barangay' => $request->input('permanent_barangay'),
                'permanent_city' => $request->input('permanent_city'),
                'permanent_province' => $request->input('permanent_province'),
                'permanent_zip_code' => $request->input('permanent_zip_code'),
                'emergency_contact_name' => $request->input(
                    'emergency_contact_name'
                ),
                'emergency_contact_address' => $request->input(
                    'emergency_contact_address'
                ),
                'emergency_contact_telephone_no' => $request->input(
                    'emergency_contact_telephone_no'
                ),
                'emergency_contact_mobile_no' => $request->input(
                    'emergency_contact_mobile_no'
                ),
            ]
        );

        PdsPersonalInfo::updateOrCreate(
            ['employee_id' => $id],
            [
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'middle_name' => $request->input('middle_name'),
                'suffix' => $request->input('suffix'),
                'sex' => $request->input('sex'),
                'date_of_birth' => $request->input('date_of_birth'),
                'civil_status' => $request->input('civil_status'),
                'height' => $request->input('height'),
                'weight' => $request->input('weight'),
                'blood_type' => $request->input('blood_type'),
                'citizenship_type' => $request->input('citizenship_type'),
                'citizenship_process' => $request->input('citizenship_process'),
                'primary_country' => $request->input('primary_country'),
                'secondary_country' => $request->input('secondary_country'),
                'residential_address_line_1' => $request->input(
                    'residential_address_line_1'
                ),
                'residential_street' => $request->input('residential_street'),
                'residential_village' => $request->input('residential_village'),
                'residential_barangay' => $request->input(
                    'residential_barangay'
                ),
                'residential_city' => $request->input('residential_city'),
                'residential_province' => $request->input(
                    'residential_province'
                ),
                'residential_zip_code' => $request->input(
                    'residential_zip_code'
                ),
                'permanent_address_line_1' => $request->input(
                    'permanent_address_line_1'
                ),
                'permanent_street' => $request->input('permanent_street'),
                'permanent_village' => $request->input('permanent_village'),
                'permanent_barangay' => $request->input('permanent_barangay'),
                'permanent_city' => $request->input('permanent_city'),
                'permanent_province' => $request->input('permanent_province'),
                'permanent_zip_code' => $request->input('permanent_zip_code'),
            ]
        );

        //        $this->logEvent(Auth::user()->id, 'Added an employee with an id: '.Employee::with('Designation')->latest()->first()->id.'.', trans('responses.status.success'));

        return customResponse()
            ->data(Employee::with('profile')->find($employee->employee_id))
            ->message('Employee has been updated.')
            ->success()
            ->generate();
    }

    public function updateEmployeeAppointment(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return customResponse()
                ->data(null)
                ->message('Employee not found.')
                ->notFound()
                ->generate();
        }

        $latestAppointmentHistory = EmployeeAppointmentHistory::where(
            'employee_id',
            $id
        )
            ->latest()
            ->get()
            ->first();

        if ($latestAppointmentHistory) {
            $latestAppointmentHistory->update([
                'end_date' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'plantilla_id' => 'required',
            ]);

            if ($validator->fails()) {
                return customResponse()
                    ->data(null)
                    ->message($validator->errors()->all()[0])
                    ->failed()
                    ->generate();
            }
            $employeeAppointment = Employee::updateOrCreate(
                ['employee_id' => $id],
                [
                    'department_id' => $request->input('department_id'),
                    'plantilla_id' => $request->input('plantilla_id'),
                    'office_id' => $request->input('office_id'),
                    'employment_status_id' => $request->input(
                        'employment_status_id'
                    ),
                    'employment_status_start_date' => $request->input(
                        'employment_status_start_date'
                    ),
                    'employment_status_end_date' => $request->input(
                        'employment_status_end_date'
                    ),
                    'appointment_nature_id' => $request->input(
                        'appointment_nature_id'
                    ),
                    'remarks' => $request->input('remarks'),
                    'salary_grade_id' => $request->input('salary_grade_id'),
                    'unit_id' => $request->input('unit_id'),
                    'position_id' => $request->input('position_id'),
                    'step_increment_id' => $request->input('step_increment_id'),
                ]
            );

            EmployeeAppointmentHistory::create([
                'employee_id' => $employee->employee_id,
                'plantilla_id' => $request->input('plantilla_id'),
                'office_id' => $request->input('office_id'),
                'department_id' => $request->input('department_id'),
                'salary_grade_id' => $request->input('salary_grade_id'),
                'position_id' => $request->input('position_id'),
                'unit_id' => $request->input('unit_id'),
                'step_increment_id' => $request->input('step_increment_id'),
                'employment_status_id' => $request->input(
                    'employment_status_id'
                ),
                'employment_status_start_date' => $request->input(
                    'employment_status_start_date'
                ),
                'employment_status_end_date' => $request->input(
                    'employment_status_end_date'
                ),
                'appointment_nature_id' => $request->input(
                    'appointment_nature_id'
                ),
                'remarks' => $request->input('remarks'),
            ]);

            return customResponse()
                ->data(
                    Employee::with([
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
                    ])->find($employeeAppointment->employee_id)
                )
                ->message('Employee Appointment has been updated.')
                ->success()
                ->generate();
        }

        $inputStartDate = new Carbon(
            $request->input('employment_status_start_date')
        );
        $historyEndDate = !$latestAppointmentHistory->employment_status_end_date
            ? Carbon::now()->toDate()
            : new Carbon($latestAppointmentHistory->employment_status_end_date);

        if ($inputStartDate->lte($historyEndDate)) {
            $latestAppointmentHistory->update([
                'employment_status_end_date' => $inputStartDate->subDay(),
            ]);
        } elseif (!$latestAppointmentHistory->employment_status_end_date) {
            $latestAppointmentHistory->update([
                'employment_status_end_date' => $inputStartDate->subDay(),
            ]);
        }

        EmployeeAppointmentHistory::create([
            'employee_id' => $employee->employee_id,
            'plantilla_id' => $request->input('plantilla_id'),
            'office_id' => $request->input('office_id'),
            'department_id' => $request->input('department_id'),
            'salary_grade_id' => $request->input('salary_grade_id'),
            'position_id' => $request->input('position_id'),
            'unit_id' => $request->input('unit_id'),
            'step_increment_id' => $request->input('step_increment_id'),

            'employment_status_id' => $request->input('employment_status_id'),
            'appointment_nature_id' => $request->input('appointment_nature_id'),
            'employment_status_start_date' => $request->input(
                'employment_status_start_date'
            ),
            'employment_status_end_date' => $request->input(
                'employment_status_end_date'
            ),
            'remarks' => $request->input('remarks'),
        ]);

        if ($request->input('position_id')) {
            if (
                (int) $request->input('position_id') !==
                (int) $employee->position_id
            ) {
                PreviousPosition::create([
                    'employee_id' => $id,
                    'position_id' => (int) $employee->position_id,
                ]);
            }
        }

        if ($request->input('plantilla_id')) {
            if (
                (int) $request->input('plantilla_id') !==
                (int) $employee->plantilla_id
            ) {
                PreviousPlantilla::create([
                    'employee_id' => $id,
                    'plantilla_id' => (int) $employee->plantilla_id,
                ]);
            }
        }

        $employeeAppointment = Employee::updateOrCreate(
            ['employee_id' => $id],
            [
                'department_id' => $request->input('department_id'),
                'plantilla_id' => $request->input('plantilla_id'),
                'office_id' => $request->input('office_id'),
                'employment_status_id' => $request->input(
                    'employment_status_id'
                ),
                'appointment_nature_id' => $request->input(
                    'appointment_nature_id'
                ),
                'remarks' => $request->input('remarks'),
                'salary_grade_id' => $request->input('salary_grade_id'),
                'unit_id' => $request->input('unit_id'),
                'position_id' => $request->input('position_id'),
                'step_increment_id' => $request->input('step_increment_id'),
            ]
        );

        return customResponse()
            ->data(
                Employee::with([
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
                ])->find($employeeAppointment->employee_id)
            )
            ->message('Employee Appointment has been updated.')
            ->success()
            ->generate();
    }

    public function updateEmployeeReport(Request $request, $id)
    {
        $employeeReport = EmployeeReport::updateOrCreate(
            ['employee_id' => $id],
            [
                'office_id' => $request->input('office_id'),
                'department_id' => $request->input('department_id'),
                'unit_id' => $request->input('unit_id'),
            ]
        );

        return customResponse()
            ->data(
                EmployeeReport::with(['employee'])->find(
                    $employeeReport->employee_report_id
                )
            )
            ->message('Employee Report has been updated.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $employee = Employee::with([
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
        ])->findOrFail((int) $id);

        return customResponse()
            ->data($employee)
            ->message('Employee successfully found.')
            ->success()
            ->generate();
    }

    public function showViaEmployeeNumber($id)
    {
        $employee = Employee::with([
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
            ->where('employee_no', $id)
            ->firstOrFail();

        return customResponse()
            ->data($employee)
            ->message('Employee successfully found.')
            ->success()
            ->generate();
    }

    public function showEmployeeAppointment($id)
    {
        $employee = Employee::with([
            'department',
            'employmentStatus',
            'appointmentNature',
            'unit',
            'stepIncrement',
            'plantilla',
            'position',
            'salaryGrade',
            'office',
        ])->findOrFail((int) $id, [
            'department_id',
            'employment_status_id',
            'appointment_nature_id',
            'unit_id',
            'step_increment_id',
            'plantilla_id',
            'position_id',
            'salary_grade_id',
            'office_id',
        ]);

        return customResponse()
            ->data($employee)
            ->message('Employee successfully found.')
            ->success()
            ->generate();
    }

    public function showEmployeeAppointmentHistory($id)
    {
        $employeeAppointmentHistories = EmployeeAppointmentHistory::with([
            'department',
            'employmentStatus',
            'appointmentNature',
            'unit',
            'stepIncrement',
            'plantilla',
            'position',
            'salaryGrade',
            'office',
        ])
            ->where('employee_id', $id)
            ->latest()
            ->get();

        return customResponse()
            ->data($employeeAppointmentHistories)
            ->message('Employee Appointment Histories successfully found.')
            ->success()
            ->generate();
    }

    public function showEmployeeReport($id)
    {
        $employeeReport = EmployeeReport::with(['department', 'unit', 'office'])
            ->where('employee_id', $id)
            ->get()
            ->first();

        return customResponse()
            ->data($employeeReport)
            ->message('Employee Report successfully found.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->delete();
            return customResponse()
                ->data($employee)
                ->message('Employee successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Employee not found.')
            ->notFound()
            ->generate();
    }

    public function generateEmployeeNumber($elected)
    {
        if ($elected) {
            $count =
                Employee::where('employee_no', 'LIKE', '%E-%')
                    ->get()
                    ->count() + 1;
            return 'E-' . str_pad($count, 5, '0', STR_PAD_LEFT);
        }

        $count =
            Employee::where('employee_no', 'NOT LIKE', '%E-%')
                ->get()
                ->count() + 1;
        return str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function generatePassword(
        $firstName,
        $middleName,
        $lastName,
        $dateOfBirthdate
    ) {
        $firstName = strtolower($firstName);
        $lastName = strtolower($lastName);
        if ($middleName) {
            $middleName = strtolower($middleName);
            return substr($firstName, 0, 1) .
                substr($firstName, -1, 1) .
                substr($middleName, 0, 1) .
                substr($middleName, -1, 1) .
                substr($lastName, 0, 1) .
                substr($lastName, -1, 1) .
                $dateOfBirthdate;
        }
        return substr($firstName, 0, 1) .
            substr($firstName, -1, 1) .
            substr($lastName, 0, 1) .
            substr($lastName, -1, 1) .
            $dateOfBirthdate;
    }

    public function combineNames($firstName, $middleName, $lastName, $suffix)
    {
        if ($middleName && !$suffix) {
            return $firstName . ' ' . $middleName . ' ' . $lastName;
        }
        if ($suffix && $middleName) {
            return $firstName .
                ' ' .
                $middleName .
                ' ' .
                $lastName .
                ' ' .
                $suffix;
        }

        if ($suffix && !$middleName) {
            return $firstName . ' ' . $lastName . ' ' . $suffix;
        }
        return $firstName . ' ' . $lastName;
    }

    public function changePassword(Request $request, $employeeId)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        if (
            $request->input('new_password') !=
            $request->input('confirm_password')
        ) {
            return customResponse()
                ->message('Confirm password incorrect!')
                ->success(400)
                ->generate();
        }

        $hashedPassword = User::find($employeeId)->value('password');

        Log::debug('chagne passeord');
        Log::Debug($employeeId);
        Log::debug($request->all());
        Log::debug($request->current_password);
        Log::debug($request->new_password);
        Log::debug($request->confirm_password);
        Log::debug($hashedPassword);

        // current password validation
        if (!Hash::check($request->current_password, $hashedPassword)) {
            return customResponse()
                ->message('Current password incorrect!')
                ->success(400)
                ->generate();
        }

        // new password is the old password
        if (Hash::check($request->new_password, $hashedPassword)) {
            return customResponse()
                ->message('New password cannot be old password!')
                ->success(400)
                ->generate();
        }

        // save new password
        $user = User::find($employeeId);
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return customResponse()
            ->message('Password updated successfully')
            ->success(201)
            ->generate();
    }

    public function isNameUnique($name)
    {
        return Employee::where(function ($query) use ($name) {
            return $query
                ->where('first_name', $name['first_name'])
                ->where('middle_name', $name['middle_name'])
                ->where('last_name', $name['last_name'])
                ->where('suffix', $name['suffix']);
        })
            ->get()
            ->first();
    }

    public function generateIDs(Request $request)
    {
        $employeeIds = $request->input('employee_ids');

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
            'pdsPersonalInfo',
            'pdsFamilyBackground',
        ])
            ->whereIn('tbl_employees.employee_id', $employeeIds)
            ->get();

        return customResponse()
            ->success()
            ->message('Success in Generating IDs')
            ->data($employees)
            ->generate();
    }

    public function generateIDsWithTCPDF(Request $request)
    {
        header('Access-Control-Allow-Origin: ' . config('app.app_allow_origins'));
        header('Access-Control-Allow-Credentials: true');

        /**
         * Define constants
         */
        define('PAGE_ORIENTATION', 'L');

        define('CARD_WIDTH_PX', 336);
        define('CARD_HEIGHT_PX', 480);

        // define('PAGE_FORMAT', [
        //     10 + .10, 
        //     7 + .05
        // ]);

        // 420, 595
        define('PAGE_FORMAT', 'A5');

        define('PAGE_UNITS', 'in');

        // margins
        define('MARGIN_TOP', 0);
        define('MARGIN_LEFT', 0);
        define('MARGIN_RIGHT', 0);

        
        /**
         * Initialize settings
         */
        $pdf = customPDF();

        $pdf::SetMargins(0, 0, 0);
        $pdf::SetHeaderMargin(0);

        $pdf::SetPageUnit(PAGE_UNITS);

        $pdf::SetMargins(
            MARGIN_LEFT,
            MARGIN_TOP,
            MARGIN_RIGHT,
            true
        );

        $pdf::SetAutoPageBreak(false, 0);


        /**
         * File info
         */
        $date = Carbon::now()->toDateTimeString();
        $fileName = "Malabon IDs - {$date}.pdf";

        $author = User::with(['profile'])->find(Auth::id());

        $pdf::SetCreator(env('APP_NAME'));
        $pdf::SetAuthor($author->profile->last_name);
        $pdf::SetTitle($fileName);
        $pdf::SetSubject('Malabon IDs');
        $pdf::SetKeywords('Malabon, ID, Identification');

        /**
         * Default profile image
         */
        // $photoPath = "public/images/id/defaults/default-photo.png";
        // $photoExt = explode(".", $photoPath);
        // $photoExt = end($photoExt);
        // $photoImg = base64_encode(Storage::get($photoPath));
        // $photoSrc = "data:image/{$photoExt};base64,{$photoImg}";

        // $frontBgImage = file_get_contents(
        //     realpath($_SERVER['DOCUMENT_ROOT']) . '\assets\id_bg_front.png'
        // );
        // $frontBgImageBase64 =
        //     'data:image/' . 'png' . ';base64,' . base64_encode($frontBgImage);

        /**
         * Get data
         */
        $employeeIds = $request->input('employee_ids', []);

        $employees = Employee::with([
                'profile',
                'department',
                'unit',
                'position',
                'office',
                'designation',
                'personal',
                'pdsFamilyBackground',
            ])
            ->whereIn('tbl_employees.employee_id', $employeeIds)
            ->get();

        /**
         * Layout
         */
        $dateOfIssue = Carbon::now()->format('m/d/Y');

        $bg = Storage::get('public/images/id/id-a5-bg.png');
        $pdf::Image('@' . $bg, .1, .13, 0, 0, '', '', '', 0, false);

        foreach ($employees as $employee) {

            $pdf::AddPage(PAGE_ORIENTATION, PAGE_FORMAT);
            
            $constant = 'constant';

            // set data
            $office = strtoupper($employee->office->office_name);
            $employeeNo = strtoupper($employee->employee_no);
            $completeName = strtoupper($employee->complete_name);
            $position = strtoupper($employee->position->position_name);

            $completeAddress = strtoupper(
                $employee->profile->complete_residential_address
            );

            $emergencyContactName = strtoupper($employee->profile->emergency_contact_name);
            $emergencyContactAddress = strtoupper($employee->profile->emergency_contact_address);
            $emergencyContactTelephone = strtoupper($employee->profile->emergency_contact_telephone_no);

            $bloodType = $employee->personal->blood_type;
            $telephoneNo = $employee->personal->telephone_no;

            $officeHead = $employee->office_head;

            // generate qr code
            if ($employee->qr_code == null) {
                $employee->qr_code = Hash::make(
                    uniqid('QR', true)
                );
                $employee->save();
            }

            $employeeQRCode = QrCode::format('svg')
                ->size(130)
                ->errorCorrection('H')
                ->generate('manila');

            Log::debug($employeeQRCode);

            // backgrounds
            // $frontBg = Storage::get('public/images/id/front-bg.svg');

            // html
            // background-color: #DA55AA;
            // background-color: white;
            $html = <<<HTML

                <table
                    cellspacing="8"
                    cellpadding="0"
                    cellmargin="0"
                >
                    <tr>
                        <td><table
                                cellspacing="0"
                                cellpadding="8"
                                cellmargin="0"
                            >
                                <tr>
                                    <td 
                                        style="
                                            border: 1px solid #E73E98;
                                            width: {$constant('CARD_WIDTH_PX')};
                                            height: {$constant('CARD_HEIGHT_PX')};
                                            padding: 0px;
                                            margin: 0px;
                                            text-align: center;
                                        "
                                    ><!-- front id 
                                    --><table
                                            cellspacing="4"
                                            cellpadding="0"
                                            cellmargin="0"
                                            style="
                                                text-align: center;
                                            "
                                        >
                                            <!-- header -->
                                            <tr>
                                                <td style="
                                                    font-size: 0px;"
                                                    colspan="2"
                                                ></td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 17px;
                                                    text-align: center;
                                                    height: 24px;"
                                                    colspan="2"

                                                >Republic of the Philippines</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 18px;
                                                    font-weight: bold;
                                                    height: 22px;
                                                    text-align: center;
                                                    background-color: white;"
                                                    colspan="2"
                                                
                                                >CITY GOVERNMENT OF MALABON</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 2px;
                                                    border-bottom: 2px solid white;"
                                                    colspan="2"
                                                ></td>
                                            </tr>

                                            <!-- photo and qr -->
                                            <tr><td style="font-size: 1; height: 2px;"></td></tr>

                                            <tr>
                                                <td style="
                                                    width: 175px;
                                                    height: 198px;
                                                    background-color: #FC96CA;
                                                    "
                                                ><!-- photo
                                                -->
                                                </td>

                                                <td><!-- qr
                                                --><table
                                                        cellspacing="3"
                                                        cellpadding="0"
                                                        cellmargin="0"
                                                        style="
                                                            width: 136px;
                                                        "
                                                    >
                                                        <tr>
                                                            <td style="
                                                                height: 136px;
                                                                background-color: black;
                                                                "
                                                            ></td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                height: 57px;
                                                                background-color: white;"
                                                            ><!-- office
                                                            --><table
                                                                    cellspacing="2"
                                                                    cellpadding="0"
                                                                    cellmargin="0"
                                                                    style="
                                                                        height: 57px;"
                                                                >
                                                                    <tr>
                                                                        <td style="
                                                                            height: 30px;
                                                                            font-size: 10.5px"
                                                                        >{$office}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="
                                                                            font-weight: bold;
                                                                            font-size: 12px"
                                                                        
                                                                        ><span style="
                                                                                color: #F92F96;"

                                                                            >ID.:</span>

                                                                            {$employeeNo}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <!-- name, position, and mayor -->
                                            <tr><td style="font-size: 1; height: 2px;"></td></tr>

                                            <tr>
                                                <td style="
                                                    font-size: 24px;
                                                    font-weight: bold;
                                                    height: 60px;
                                                    width: 308px;
                                                    text-align: center;
                                                    background-color: white;
                                                    "
                                                    colspan="2"

                                                >{$completeName}</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 17px;
                                                    font-weight: medium;
                                                    height: 46px;
                                                    width: 308px;
                                                    text-align: center;
                                                    background-color: black;
                                                    color: white;
                                                    "
                                                    colspan="2"

                                                >{$position}</td>
                                            </tr>

                                            <tr><td style="font: 1px; height: 20px"></td></tr>

                                            <tr>
                                                <td style="
                                                    font-size: 13px;
                                                    font-weight: bold;
                                                    text-align: center;
                                                    "
                                                    colspan="2"

                                                >HON. ANTOLIN A. ORETA III</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 12px;
                                                    font-weight: bold;
                                                    text-align: center;
                                                    "
                                                    colspan="2"

                                                >City Mayor</td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td style="
                                border: 1px solid #E73E98;
                                width: {$constant('CARD_WIDTH_PX')};
                                height: {$constant('CARD_HEIGHT_PX')};
                                text-align: center;
                        "><!-- back id 
                        --><table
                                cellspacing="0"
                                cellpadding="0"
                                cellmargin="0"
                            >
                                <tr>
                                    <td><table
                                            cellspacing="0"
                                            cellpadding="8"
                                            cellmargin="0"
                                            style="
                                                text-align: center;
                                            "
                                        >
                                            <tr>
                                                <td><table
                                                        cellspacing="4"
                                                        cellpadding="0"
                                                        cellmargin="0"
                                                        style="
                                                            text-align: center;
                                                        "
                                                    >
                                                        <!-- address -->
                                                        <tr><td style="font-size: 1px; height: 5px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                text-align: center;
                                                                height: 36px;
                                                                "

                                                            >{$completeAddress}</td>
                                                        </tr>

                                                        <!-- emergency -->
                                                        <tr>
                                                            <td style="
                                                                height: 97px;
                                                                border: 2px solid #EB429D;
                                                                "
                                                            ><!-- emergency
                                                            --><table 
                                                                    cellspacing="4"
                                                                    cellpadding="0"
                                                                    cellmargin="0"
                                                                >
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            "
                                                                            colspan="2"

                                                                        >IN CASE OF EMERGENCY, PLEASE NOTIFY:</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            "
                                                                            colspan="2"

                                                                        >{$emergencyContactName}</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 65px;
                                                                            "
                                                                        >ADDRESS:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            width: 250px;
                                                                            "
                                                                        >{$emergencyContactAddress}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 65px;
                                                                            "
                                                                        >TEL. NO.:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            width: 250px;
                                                                            "
                                                                        >{$emergencyContactTelephone}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <!-- other-info -->
                                                        <tr>
                                                            <td><!-- emergency
                                                            --><table 
                                                                    cellspacing="4"
                                                                    cellpadding="0"
                                                                    cellmargin="0"
                                                                >
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 85px;
                                                                            "
                                                                        >BLOOD TYPE:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            width: 48px;
                                                                            "
                                                                        >{$bloodType}</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 95px;
                                                                            "
                                                                        >DATE OF ISSUE:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            width: 72px;
                                                                            "
                                                                        >{$dateOfIssue}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 85px;
                                                                            "
                                                                        >TEL. NO.:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            "
                                                                            colspan="3"
                                                                        >{$telephoneNo}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- signature of employee -->
                                                        <tr><td style="height: 35px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 0px;
                                                                text-align: center;
                                                                "
                                                            ><table align-cetner>
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid black;
                                                                            width: 121px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                "

                                                            >SIGNATURE OF EMPLOYEE</td>
                                                        </tr>

                                                        <!-- head -->
                                                        <tr><td style="height: 50px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                "
                                                            >{$officeHead}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 0px;
                                                                text-align: center;
                                                                "
                                                            ><table align-cetner>
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid black;
                                                                            width: 121px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                "
                                                            >SIGNATURE OF HEAD OF OFFICE</td>
                                                        </tr>
                                                        
                                                        <tr><td style="font-size: 1px; height: 5px"></td></tr>
                                                    </table>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        height: 100px;
                                        background-color: #FC96CA;
                                    "><table
                                            cellspacing="0"
                                            cellpadding="0"
                                            cellmargin="0"
                                            style="
                                                text-align: center;
                                            "
                                        >

                                            <!-- mission and vision -->
                                            <tr>
                                                <td><!-- mission and vision 
                                                --><table 
                                                        cellspacing="4"
                                                        cellpadding="0"
                                                        cellmargin="0"
                                                    >
                                                        <tr>
                                                            <td style="
                                                                font-size: 10px;
                                                                font-weight: bold;
                                                                color: #E73E98;
                                                                "
                                                            >VISION</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 7px;
                                                                font-weight: medium;
                                                                color: #E73E98;
                                                                "
                                                            >SENTRO NG KULINARYA SA NATIONAL CAPITAL REGION, LUNGSOD NA MAY MAYAMANG 
                                                                KASAYSAYAN, PAMANA AT KULTURA, MATATAG NA EKONOMIYA, MALINIS AT LUNTIANG 
                                                                KAPALIGIRAN, LIGTAS NA PAMAYANAN, REPONSABLE AT RESPETADONG MALABONIAN SA 
                                                                ILALIM NG MAPAGKALINGA, ORGANISADO AT MATAPAT NA PAMAHALAAN.
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 10px;
                                                                font-weight: bold;
                                                                color: #E73E98;
                                                                "
                                                            >MISSION</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 7px;
                                                                font-weight: medium;
                                                                color: #E73E98;
                                                                "
                                                            >MAGBIGAY NANG ANGKOP AT SAPAT NA PAGLILINGKOD MULA SA PUSO PARA SA LAHAT.</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            HTML;
            
            $pdf::writeHTML($html, true, true, true, false, '');

            $pdf::ImageSVG('@' . $employeeQRCode, 2.8, 1.35, '', '', '', '', '', 1, false);

            // $frontBg = Storage::get('public/images/id/front-bg.svg');
            // $pdf::ImageSVG('@' . $frontBg, .1, .13, 0, 0, '', '', '', 0, false);

            $bg = Storage::get('public/images/id/id-a5-bg.png');
            $pdf::Image('@' . $bg, .1, .13, 0, 0, 
                '', '', '', 0, false
            );
        }

        // Generate pdf
        $pdf::Output($fileName);
    }

    public function generateIDsWithDomPDF(Request $request)
    {
        $pdf = App::make('dompdf.wrapper');

        /**
         * Define constants
         */
        define('CARD_WIDTH', 3.5);
        define('CARD_HEIGHT', 5);


        /**
         * Settings
         */
        $pdf->setPaper('a5', 'landscape');


        /**
         * Get data
         */
        $employeeIds = $request->input('employee_ids', []);

        $employees = Employee::with([
                'profile',
                'department',
                'unit',
                'position',
                'office',
                'designation',
                'personal',
                'pdsFamilyBackground',
            ])
            ->whereIn('tbl_employees.employee_id', $employeeIds)
            ->get();

        /**
         * Layout
         */
        $dateOfIssue = Carbon::now()->format('m/d/Y');

        // fonts
        $fontInterPath = storage_path('fonts\Inter-Regular.ttf');
        $fontInterBoldPath = storage_path('fonts\Inter-Bold.ttf');
        $fontInterExtraBoldPath = storage_path('fonts\Inter-ExtraBold.ttf');

        // style
        $html = <<<HTML
            <style>
                @font-face {
                    font-family: 'Inter';
                    font-weight: normal;
                    src: url({$fontInterPath}) format('truetype');
                }

                @font-face {
                    font-family: 'Inter';
                    font-weight: 'bold';
                    src: url({$fontInterBoldPath}) format('truetype');
                }

                @font-face {
                    font-family: 'Inter';
                    font-weight: 'extra-bold';
                    src: url({$fontInterExtraBoldPath}) format('truetype');
                }

                body {
                    margin: 0;
                    padding: 0;
                    font-family: Inter;
                }

                .id-set {
                    page-break-inside: avoid;
                    margin: 0;
                    padding: 0;
                }

                @page {
                    margin: 0px 0px 0px 0px !important;
                    padding: 0px 0px 0px 0px !important;
                }
            </style>
        HTML;

        foreach ($employees as $employee) {

            $constant = 'constant';

            // set data
            $office = strtoupper($employee->office->office_name);
            $employeeNo = strtoupper($employee->employee_no);
            $completeName = strtoupper($employee->complete_name);
            $position = strtoupper($employee->position->position_name);

            $completeAddress = strtoupper(
                $employee->profile->complete_residential_address
            );

            $emergencyContactName = strtoupper($employee->profile->emergency_contact_name);
            $emergencyContactAddress = strtoupper($employee->profile->emergency_contact_address);
            $emergencyContactTelephone = strtoupper($employee->profile->emergency_contact_telephone_no);

            $bloodType = $employee->personal->blood_type;
            $telephoneNo = $employee->personal->telephone_no;

            $officeHead = $employee->office_head;

            // images
            $frontBG = base64_encode(
                Storage::get('public/images/id/front-bg.png')
            );

            $backBG = base64_encode(
                Storage::get('public/images/id/back-bg.png')
            );

            // generate qr code
            if ($employee->qr_code == null) {
                $employee->qr_code = Hash::make(
                    uniqid('QR', true)
                );
                $employee->save();
            }

            $employeeQRCode = base64_encode(
                QrCode::format('svg')
                    ->size(130)
                    ->errorCorrection('H')
                    ->generate('manila')
            );

            $html .= <<<HTML
                <table
                    class="id-set"
                >
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td 
                                        style="
                                            width: {$constant('CARD_WIDTH')}in;
                                            height: {$constant('CARD_HEIGHT')}in;
                                            max-width: {$constant('CARD_WIDTH')}in;
                                            max-height: {$constant('CARD_HEIGHT')}in;
                                            margin: 0px;
                                            text-align: center;
                                            background-image: url(data:image/png;base64,{$frontBG});
                                            background-repeat: no-repeat;
                                            padding: 7px;
                                        "
                                    ><!-- front id 
                                    --><table
                                            style="
                                                text-align: center;
                                            "
                                        >
                                            <!-- header -->
                                            <tr>
                                                <td style="
                                                    font-size: 0px;"
                                                    colspan="2"
                                                ></td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 17px;
                                                    text-align: center;
                                                    height: 24px;
                                                    font-family: Inter;
                                                    line-height: 17px;
                                                    padding: 0;
                                                    margin: 0;
                                                    margin-bottom: 4px;
                                                    "
                                                    colspan="2"

                                                >Republic of the Philippines</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 18px;
                                                    height: 22px;
                                                    text-align: center;
                                                    background-color: white;
                                                    font-family: Inter;
                                                    font-weight: bold;
                                                    line-height: 18px;
                                                    "
                                                    colspan="2"
                                                >CITY GOVERNMENT OF MALABON</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 2px;
                                                    border-bottom: 2px solid white;"
                                                    colspan="2"
                                                ></td>
                                            </tr>

                                            <!-- photo and qr -->
                                            <tr><td style="
                                                    font-size: 1; 
                                                    height: 2px; 
                                                    margin: 0; 
                                                    padding: 0;"
                                                ></td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    width: 170px;
                                                    height: 198px;
                                                    max-height: 198px;
                                                    background-color: #FC96CA;
                                                    "
                                                ><!-- photo
                                                -->
                                                </td>

                                                <td style="
                                                    padding: 0;
                                                    padding-left: 8px;
                                                    margin: 0;
                                                "><!-- qr
                                                --><table
                                                        style="
                                                            width: 136px;
                                                            padding: 0;
                                                            margin: 0;
                                                        "
                                                    >
                                                        <tr>
                                                            <td style="
                                                                height: 136px;
                                                                background-color: black;
                                                                padding: 0;
                                                                margin: 0;
                                                                "
                                                            >
                                                                <img src="data:image/svg;base64,{$employeeQRCode}">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                height: 57px;
                                                                max-height: 57px;
                                                                background-color: white;
                                                                padding: 0;
                                                                margin: 0;
                                                                "
                                                            ><!-- office
                                                            --><table
                                                                    style="
                                                                        height: 57px;
                                                                        text-align: center;
                                                                        padding: 0;
                                                                        margin: 0;
                                                                    "
                                                                >
                                                                    <tr>
                                                                        <td style="
                                                                            height: 35px;
                                                                            max-height: 35px;
                                                                            font-size: 10px
                                                                            line-height: 8px;
                                                                            font-family: Inter;
                                                                            font-weight: bold;
                                                                            text-align: center;
                                                                            padding: 0;
                                                                            margin: 0;
                                                                            "
                                                                        >{$office}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="
                                                                            font-weight: bold;
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            padding: 0;
                                                                            margin: 0;
                                                                            "
                                                                        
                                                                        ><span style="
                                                                                color: #F92F96;"

                                                                            >ID.:</span>

                                                                            {$employeeNo}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <!-- name, position, and mayor -->

                                            <tr>
                                                <td style="
                                                    font-size: 24px;
                                                    font-weight: bold;
                                                    height: 50px;
                                                    max-height: 50px;
                                                    text-align: center;
                                                    background-color: white;
                                                    line-height: 18px;
                                                    padding: 4px;
                                                    margin: 0;
                                                    "
                                                    colspan="2"

                                                >{$completeName}</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 17px;
                                                    font-weight: medium;
                                                    height: 40px;
                                                    max-height: 40px;
                                                    text-align: center;
                                                    background-color: black;
                                                    color: white;
                                                    line-height: 14px;
                                                    padding: 4px;
                                                    "
                                                    colspan="2"

                                                >{$position}</td>
                                            </tr>

                                            <tr><td style="font: 1px; height: 20px"></td></tr>

                                            <tr>
                                                <td style="
                                                    font-size: 13px;
                                                    font-weight: bold;
                                                    text-align: center;
                                                    line-height: 7px;
                                                    "
                                                    colspan="2"

                                                >HON. ANTOLIN A. ORETA III</td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                    font-size: 12px;
                                                    text-align: center;
                                                    line-height: 10px;
                                                    "
                                                    colspan="2"

                                                >City Mayor</td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td style="
                                width: {$constant('CARD_WIDTH')}in;
                                height: {$constant('CARD_HEIGHT')}in;
                                max-width: {$constant('CARD_WIDTH')}in;
                                max-height: {$constant('CARD_HEIGHT')}in;
                                text-align: center;
                                margin: 0;
                                padding: 0;
                                padding-top: 7px;
                        "><!-- back id 
                        --><table style="
                                    text-align: center;
                                    padding: 0;
                                    margin: 0;
                                    background-image: url(data:image/png;base64,{$backBG});
                                    background-repeat: no-repeat;
                                    width: 100%;
                                    "
                            >
                                <tr>
                                    <td>
                                        <table
                                            style="
                                                text-align: center;
                                                padding: 0;
                                                margin: 0;
                                            "
                                        >
                                            <tr>
                                                <td>
                                                    <table
                                                        style="
                                                            text-align: center;
                                                            padding: 0;
                                                            margin: 0;
                                                        "
                                                    >
                                                        <!-- address -->
                                                        <tr><td style="font-size: 1px; height: 5px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                text-align: center;
                                                                height: 36px;
                                                                max-height: 36px;
                                                                line-height: 12px;
                                                                "

                                                            >{$completeAddress}</td>
                                                        </tr>

                                                        <!-- emergency -->
                                                        <tr>
                                                            <td style="
                                                                height: 97px;
                                                                border: 2px solid #EB429D;
                                                                "
                                                            ><!-- emergency
                                                            --><table style="
                                                                    text-align: center;
                                                                    width: 100%;
                                                                ">
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: center;
                                                                            line-height: 12px;
                                                                            "
                                                                            colspan="2"

                                                                        >IN CASE OF EMERGENCY, PLEASE NOTIFY:</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            height: 24px;
                                                                            max-height: 24px;
                                                                            line-height: 10px
                                                                            text-align: center
                                                                            "
                                                                            colspan="2"

                                                                        >{$emergencyContactName}</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 65px;
                                                                            "
                                                                        >ADDRESS:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            height: 24px;
                                                                            max-height: 24px;
                                                                            line-height: 10px
                                                                            "
                                                                        >{$emergencyContactAddress}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 65px;
                                                                            "
                                                                        >TEL. NO.:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            height: 14px;
                                                                            max-height: 14px;
                                                                            line-height: 10px
                                                                            "
                                                                        >{$emergencyContactTelephone}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <!-- other-info -->
                                                        <tr>
                                                            <td><!-- other info
                                                            --><table 
                                                                >
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 80px;
                                                                            "
                                                                        >BLOOD TYPE:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            width: 40px;
                                                                            "
                                                                        >{$bloodType}</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 100px;
                                                                            "
                                                                        >DATE OF ISSUE:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            "
                                                                        >{$dateOfIssue}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 12px;
                                                                            text-align: left;
                                                                            width: 80px;
                                                                            "
                                                                        >TEL. NO.:</td>

                                                                        <td style="
                                                                            font-size: 12px;
                                                                            font-weight: bold;
                                                                            text-align: left;
                                                                            "
                                                                            colspan="3"
                                                                        >{$telephoneNo}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- signature of employee -->
                                                        <tr><td style="height: 30px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 0px;
                                                                text-align: center;
                                                                "
                                                            ><table align-cetner>
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid transparent;
                                                                            width: 33%;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid black;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid transparent;
                                                                            width: 100px;
                                                                        "></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                line-height: 10px;
                                                                padding: 0;
                                                                "

                                                            >SIGNATURE OF EMPLOYEE</td>
                                                        </tr>

                                                        <!-- head -->
                                                        <tr><td style="height: 50px"></td></tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                line-height: 10px;
                                                                "
                                                            >{$officeHead}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 0px;
                                                                text-align: center;
                                                                "
                                                            ><table align-cetner>
                                                                    <tr>
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid black;
                                                                            width: 100px;
                                                                        "></td>
                                                                        
                                                                        <td style="
                                                                            font-size: 1px;
                                                                            border-bottom: 2px solid white;
                                                                            width: 100px;
                                                                        "></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 12px;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                line-height: 10px;
                                                                "
                                                            >SIGNATURE OF HEAD OF OFFICE</td>
                                                        </tr>
                                                        
                                                        <tr><td style="font-size: 1px; height: 5px"></td></tr>
                                                    </table>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        height: 100px;
                                    "><table
                                            style="
                                                text-align: center;
                                            "
                                        >

                                            <!-- mission and vision -->
                                            <tr>
                                                <td><!-- mission and vision 
                                                --><table style="
                                                        text-align: center;
                                                    ">
                                                        <tr>
                                                            <td style="
                                                                font-size: 10px;
                                                                font-weight: bold;
                                                                color: #E73E98;
                                                                line-height: 8px;
                                                                "
                                                            >VISION</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 7px;
                                                                font-weight: medium;
                                                                color: #E73E98;
                                                                line-height: 6px;
                                                                "
                                                            >SENTRO NG KULINARYA SA NATIONAL CAPITAL REGION, LUNGSOD NA MAY MAYAMANG 
                                                                KASAYSAYAN, PAMANA AT KULTURA, MATATAG NA EKONOMIYA, MALINIS AT LUNTIANG 
                                                                KAPALIGIRAN, LIGTAS NA PAMAYANAN, REPONSABLE AT RESPETADONG MALABONIAN SA 
                                                                ILALIM NG MAPAGKALINGA, ORGANISADO AT MATAPAT NA PAMAHALAAN.
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 10px;
                                                                font-weight: bold;
                                                                color: #E73E98;
                                                                line-height: 8px;
                                                                "
                                                            >MISSION</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="
                                                                font-size: 7px;
                                                                font-weight: medium;
                                                                color: #E73E98;
                                                                line-height: 6px;
                                                                "
                                                            >MAGBIGAY NANG ANGKOP AT SAPAT NA PAGLILINGKOD MULA SA PUSO PARA SA LAHAT.</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            HTML;
        }
        
        $pdf->loadHTML($html);
        return $pdf->stream();
    }


    /**
     * Utilities
     */
    private function px2pt($px = 0)
    {
        return $px * 0.75;
    }

    private function in2pt($in = 0)
    {
        return $in * 72;
    }

    private function in2mm($in = 0)
    {
        return $in * 25.4;
    }
    

    public function reindexEmployeeNo()
    {
        $electedEmployees = Employee::
            where('employee_no', 'LIKE', 'E-%')
            ->orderBy('employee_id', 'ASC')
            ->get();

        $i = 1;
        foreach($electedEmployees as $employee) {
            $employeeNo = 'E-' . str_pad($i, 5, '0', STR_PAD_LEFT);

            $user = User::where('user_id', $employee->user_id)->first();
            $profile = EmployeeProfile::where('employee_id', $employee->employee_id)->first();

            // update
            $employee->update([
                'employee_no' => $employeeNo
            ]);

            $user->update([
                'username' => $employeeNo
            ]);
            
            $profile->update([
                'employee_no' => $employeeNo
            ]);

            $i++;
        }

        // not elected
        $employees = Employee::
            where('employee_no', 'NOT LIKE', 'E-%')
            ->orWhere('employee_no', 'LIKE', 'T-%')
            ->orWhere('employee_no', 'LIKE', 'TEMP-%')
            ->orWhere('employee_no', 'LIKE', '00%')
            ->orderBy('employee_id', 'ASC')
            ->get();

        // temporary
        $i = 1;
        foreach($employees as $employee) {
            $employeeNo = 'T-'.$employee->employee_id . $employee->last_name;

            $user = User::where('user_id', $employee->user_id)->first();
            $profile = EmployeeProfile::where('employee_id', $employee->employee_id)->first();

            // update
            $employee->update([
                'employee_no' => $employeeNo
            ]);

            $user->update([
                'username' => $employeeNo
            ]);
            
            if ($profile != null) {
                $profile->update([
                    'employee_no' => $employeeNo
                ]);
            }

            $i++;
        }

        // change
        $employees = Employee::
            where('employee_no', 'NOT LIKE', 'E-%')
            ->orWhere('employee_no', 'LIKE', 'T-%')
            ->orWhere('employee_no', 'LIKE', 'TEMP-%')
            ->orWhere('employee_no', 'LIKE', '00%')
            ->orderBy('employee_id', 'ASC')
            ->get();

        $i = 1;
        foreach($employees as $employee) {
            $employeeNo = str_pad($i, 5, '0', STR_PAD_LEFT);

            $user = User::where('user_id', $employee->user_id)->first();
            $profile = EmployeeProfile::where('employee_id', $employee->employee_id)->first();

            // update
            $employee->update([
                'employee_no' => $employeeNo
            ]);

            $user->update([
                'username' => $employeeNo
            ]);
            
            if ($profile != null) {
                $profile->update([
                    'employee_no' => $employeeNo
                ]);
            }

            $i++;
        }
    }
}
