<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryGradeVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_salary_grade_versions';

    protected $primaryKey = 'salary_grade_version_id';

    protected $fillable = [
        'version_year',
        'version_desc',
        'activated'
    ];

    public function actualSalaryGrades()
    {
        return $this->hasMany(
            ActualSalaryGrade::class, 
            'salary_grade_version_id', 
            'salary_grade_version_id'
        );
    }
}
