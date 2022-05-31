<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_employee_loans';

    protected $primaryKey = 'employee_loan_id';

    protected $fillable = [
        'employee_id',
        'loan_type_id',
        'amount',
        'period_start',
        'period_end'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class,
            'employee_id',
            'employee_id'
        );
    }

    public function loanType()
    {
        return $this->hasOne(References\LoanType::class,
            'loan_type_id',
            'loan_type_id'
        );
    }

    public function deductions()
    {
        return $this->hasMany(EmployeeDeduction::class,
            'employee_loan_id',
            'employee_loan_id'
        );  
    }
}
