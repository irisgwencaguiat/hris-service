<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPayrollType extends Model
{
    use HasFactory;

    public $table = 'ref_payroll_types';

    public $primaryKey = 'payroll_type_id';

    public $guarded = [];
}
