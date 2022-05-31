<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pds extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_pds';

    protected $primaryKey = 'pds_id';

    protected $guarded = [];
}
