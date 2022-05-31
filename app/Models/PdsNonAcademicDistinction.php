<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsNonAcademicDistinction extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_non_academic_distinctions';

    protected $primaryKey = 'pds_non_academic_distinction_id';

    protected $guarded = [];
}
