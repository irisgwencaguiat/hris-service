<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefDesignation extends Model
{
    use HasFactory;

    public $table = 'ref_designations';

    public $primaryKey = 'designation_id';

    public $guarded = [];
}
