<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeMonthlySalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employee_monthly_salaries';

    protected $primaryKey = 'emp_monthly_salary_id';

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'net_salary',
        'net_salary_15th',
        'net_salary_30th',
        'deduction',
        'deduction_15th',
        'deduction_30th'
    ];
}
