<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryGrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_salary_grades';

    protected $primaryKey = 'salary_grade_id';

    protected $fillable = [
        'salary_grade_name',
    ];
}
