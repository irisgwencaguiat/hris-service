<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsFamilyBackground extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_family_backgrounds';

    protected $primaryKey = 'pds_family_background_id';

    protected $guarded = [];
}
