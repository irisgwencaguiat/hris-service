<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousPosition extends Model
{
    use HasFactory;

    public $table = 'tbl_previous_positions';

    public $primaryKey = 'previous_position_id';

    public $guarded = [];

    public function position()
    {
        return $this->hasOne(
            RefPosition::class,
            'position_id',
            'position_id'
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
