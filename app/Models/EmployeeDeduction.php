<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDeduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employee_deductions';

    protected $primaryKey = 'employee_deduction_id';

    protected $fillable = [
        'employee_loan_id',
        'employee_id',
        'day',
        'deduction_date',
        'amount'
    ];
}
