<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsReference extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_references';

    protected $primaryKey = 'pds_reference_id';

    protected $guarded = [];
}
