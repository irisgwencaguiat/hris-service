<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAccountInfo extends Model
{
    use HasFactory;

    protected $table = 'tbl_payroll_account_info';

    protected $primaryKey = 'payroll_account_info_id';

    protected $guarded = [];
}
