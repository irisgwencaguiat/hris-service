<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_units';

    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_name',
        'office_id',
        'department_id',
        'have_sub_units',
        'parent_unit_id'
    ];

    public function sub_units()
    {
        return $this->hasMany(RefUnit::class, 'parent_unit_id', 'unit_id');
    }

    public function parent_unit()
    {
        return $this->belongsTo(RefUnit::class, 'unit_id', 'parent_unit_id')->withDefault();
    }

    public function office()
    {
        return $this->belongsTo(RefOffice::class, 'office_id', 'office_id')->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(RefDepartment::class, 'department_id', 'department_id')->withDefault();
    }
}
