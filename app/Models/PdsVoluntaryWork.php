<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsVoluntaryWork extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_voluntary_works';

    protected $primaryKey = 'pds_voluntary_work_id';

    protected $guarded = [];
}
