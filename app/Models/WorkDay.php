<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;

    protected $table = 'tbl_work_days';

    protected $primaryKey = 'work_day_id';

    protected $guarded = [];
}
