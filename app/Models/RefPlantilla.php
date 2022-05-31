<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPlantilla extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_plantillas';

    protected $primaryKey = 'plantilla_id';

    protected $fillable = [
        'plantilla_no',
        'position_id',
        'salary_grade_id',
        'step_increment_id',
        'office_id',
    ];

    public function position()
    {
        return $this->hasOne(
            RefPosition::class,
            'position_id',
            'position_id'
        )->withDefault();
    }

    public function office()
    {
        return $this->belongsTo(
            RefOffice::class,
            'office_id',
            'office_id'
        )->withDefault();
    }

    public function holders()
    {
        return $this->hasMany(
            Employee::class, 
            'plantilla_id', 
            'plantilla_id'
        );
    }
}
