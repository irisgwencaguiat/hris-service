<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousPlantilla extends Model
{
    use HasFactory;

    public $table = 'tbl_previous_plantillas';

    public $primaryKey = 'previous_plantilla_id';

    public $guarded = [];

    public function plantilla()
    {
        return $this->hasOne(
            RefPlantilla::class,
            'plantilla_id',
            'plantilla_id'
        )->withDefault();
    }

    public function employee()
    {
        return $this->hasOne(
            Employee::class,
            'employee_id',
            'employee_id'
        )->withDefault();
    }
}
