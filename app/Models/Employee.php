<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employees';

    protected $primaryKey = 'employee_id';

    protected $guarded = [];

    protected $searchable = [
        'employee_no',
        'email',
        'first_name',
        'last_name'
    ];

    /**
     * Additional Attribute
     */
    protected $appends = [
        'complete_name',
        'office_head',
        'department_head'
    ];

    public function getCompleteNameAttribute()
    {
        $completeName = $this->first_name;
        
        if ($this->middle_name) {
            $completeName .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }

        $completeName .= ' ' . $this->last_name;

        if ($this->suffix) {
            $completeName .= ' ' . $this->suffix;
        }

        return $completeName;
    }

    public function getOfficeHeadAttribute()
    {
        $officeHead = Employee::where([
                'office_id' => $this->office_id,
                'designation_id' => 1
            ])
            ->first();
        
        if ($officeHead == null) {
            return '';
        }

        return $officeHead->complete_name;
    }

    public function getDepartmentHeadAttribute()
    {
        $departmentHead = Employee::where([
                'department_id' => $this->department_id,
                'designation_id' => 1
            ])
            ->first();
        
        if ($departmentHead == null) {
            return '';
        }

        return $departmentHead->complete_name;
    }

    /**
     * Relationships
     */

    public function profile()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id')->withDefault();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id')->withDefault();
    }

    public function department()
    {
        return $this->hasOne(
            RefDepartment::class,
            'department_id',
            'department_id'
        )->withDefault();
    }

    public function employmentStatus()
    {
        return $this->hasOne(
            RefEmploymentStatus::class,
            'employment_status_id',
            'employment_status_id'
        )->withDefault();
    }

    public function appointmentNature()
    {
        return $this->hasOne(
            RefAppointmentNature::class,
            'appointment_nature_id',
            'appointment_nature_id'
        )->withDefault();
    }

    public function unit()
    {
        return $this->hasOne(
            RefUnit::class,
            'unit_id',
            'unit_id'
        )->withDefault();
    }

    public function stepIncrement()
    {
        return $this->hasOne(
            References\StepIncrement::class,
            'step_increment_id',
            'step_increment_id'
        )->withDefault();
    }

    public function salaryGrade()
    {
        return $this->hasOne(
            References\SalaryGrade::class,
            'salary_grade_id',
            'salary_grade_id'
        )->withDefault();
    }

    public function plantilla()
    {
        return $this->hasOne(
            RefPlantilla::class,
            'plantilla_id',
            'plantilla_id'
        )->withDefault();
    }

    public function position()
    {
        return $this->hasOne(
            RefPosition::class,
            'position_id',
            'position_id'
        )->withDefault();
    }

    public function office()
    {
        return $this->hasOne(
            RefOffice::class,
            'office_id',
            'office_id'
        )->withDefault();
    }

    public function designation()
    {
        return $this->hasOne(
            RefDesignation::class,
            'designation_id',
            'designation_id'
        )->withDefault();
    }

    public function salary()
    {
        $actualSG = ActualSalaryGrade::where([
            'salary_grade_id' => $this->salary_grade_id,
            'salary_grade_version_id' => SalaryGradeVersion::where(
                'activated',
                true
            )->first()->salary_grade_version_id,
        ])->first();

        if ($actualSG == null) {
            return 0;
        }

        $stepIncrement = References\StepIncrement::find(
            $this->step_increment_id
        );

        if ($stepIncrement == null) {
            return 0;
        }

        return doubleval(
            $actualSG->{'step_' . $stepIncrement->step_increment_name}
        );
    }

    public function personal()
    {
        return $this->hasOne(
            PdsPersonalInfo::class,
            'employee_id',
            'employee_id'
        )->withDefault();
    }

    public function pdsPersonal()
    {
        return $this->hasOne(
            PdsPersonalInfo::class,
            'employee_id',
            'employee_id'
        )->withDefault();
    }

    public function pdsFamilyBackground()
    {
        return $this->hasOne(
            PdsFamilyBackground::class,
            'employee_id',
            'employee_id'
        );
    }

    public function qrCodeImage()
    {
        // $employeeQRCode = base64_encode(
        //     QrCode::format('svg')
        //         ->size(200)
        //         ->errorCorrection('H')
        //         ->generate($id)
        return [
            1, 2, 3
        ];
    }
}
