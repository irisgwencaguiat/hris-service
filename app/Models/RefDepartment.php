<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefDepartment extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_departments';

    protected $primaryKey = 'department_id';

    protected $fillable = ['department_name', 'department_code', 'office_id'];

    public function units()
    {
        return $this->hasMany(RefUnit::class, 'department_id', 'department_id');
    }

    public function office()
    {
        return $this->belongsTo(
            RefOffice::class,
            'office_id',
            'office_id'
        )->withDefault();
    }

    public function payroll()
    {
        return $this->belongsTo(
            Payroll::class,
            'department_id',
            'department_id'
        )->withDefault();
    }
}
