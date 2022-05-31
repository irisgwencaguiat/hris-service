<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsWorkExperience extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_work_experiences';

    protected $primaryKey = 'pds_work_experience_id';

    protected $guarded = [];
}
