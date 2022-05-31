<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsGovernmentIdentification extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_government_identifications';

    protected $primaryKey = 'pds_government_identification_id';

    protected $guarded = [];
}
