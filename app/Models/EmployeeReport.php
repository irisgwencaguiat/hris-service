<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    use HasFactory;

    public $table = 'tbl_employee_reports';

    public $primaryKey = 'employee_report_id';

    public $guarded = [];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_id', 'employee_id');
    }

    public function department()
    {
        return $this->hasOne(
            RefDepartment::class,
            'department_id',
            'department_id'
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

    public function office()
    {
        return $this->hasOne(
            RefOffice::class,
            'office_id',
            'office_id'
        )->withDefault();
    }
}
