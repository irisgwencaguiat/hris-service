<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsFamilybackgroundChild extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_familybackground_children';

    protected $primaryKey = 'pds_familybackground_child_id';

    protected $guarded = [];
}
