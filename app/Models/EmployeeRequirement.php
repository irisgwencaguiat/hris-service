<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequirement extends Model
{
    use HasFactory;

    public $table = 'tbl_employee_requirements';

    public $primaryKey = 'employee_requirement_id';

    public $guarded = [];

    public function requirement()
    {
        return $this->hasOne(
            RefRequirement::class,
            'requirement_id',
            'requirement_id'
        )->withDefault();
    }
}
