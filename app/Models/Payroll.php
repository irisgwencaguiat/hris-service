<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'tbl_payrolls';

    protected $primaryKey = 'payroll_id';

    protected $guarded = [];

    public function department()
    {
        return $this->hasOne(
            RefDepartment::class,
            'department_id',
            'department_id'
        )->withDefault();
    }

    public function refPayrollType()
    {
        return $this->hasOne(
            RefPayrollType::class,
            'payroll_type_id',
            'payroll_type_id'
        )->withDefault();
    }
}
