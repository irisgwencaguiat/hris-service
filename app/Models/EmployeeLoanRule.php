<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLoanRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employee_loan_rules';

    protected $primaryKey = 'emp_loan_rule_id';

    protected $fillable = [
        'emp_loan_rule_desc',
        'min_monthly_salary_for_getting_loan',
        'activated'
    ];
}
