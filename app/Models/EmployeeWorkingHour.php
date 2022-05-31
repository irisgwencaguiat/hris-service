<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWorkingHour extends Model
{
    use HasFactory;

    protected $table = 'tbl_employee_working_hours';

    protected $primaryKey = 'employee_working_hour_id';

    protected $guarded = [];

    protected $casts = [
        'is_off' => 'boolean',
        'is_flexible' => 'boolean',
    ];
}
