<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsCsEligibility extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_cs_eligibilities';

    protected $primaryKey = 'pcse_id';

    protected $guarded = [];
}
