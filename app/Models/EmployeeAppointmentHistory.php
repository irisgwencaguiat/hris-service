<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAppointmentHistory extends Model
{
    use HasFactory;

    public $table = 'tbl_employee_appointment_histories';

    public $primaryKey = 'employee_appointment_history_id';

    public $guarded = [];

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
}
