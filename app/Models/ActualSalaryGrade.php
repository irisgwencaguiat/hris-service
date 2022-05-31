<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActualSalaryGrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_actual_salary_grades';

    protected $primaryKey = 'actual_salary_grade_id';

    protected $fillable = [
        'salary_grade_version_id',
        'salary_grade_id',
        'step_1',
        'step_2',
        'step_3',
        'step_4',
        'step_5',
        'step_6',
        'step_7',
        'step_8',
    ];
}
