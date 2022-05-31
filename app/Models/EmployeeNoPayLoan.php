<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeNoPayLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employee_no_pay_loans';

    protected $primaryKey = 'emp_no_pay_loan_id';

    protected $fillable = [
        'employee_loan_id',
        'employee_id',
        'period_start',
        'period_end'
    ];
}
