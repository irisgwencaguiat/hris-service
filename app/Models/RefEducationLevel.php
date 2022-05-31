<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefEducationLevel extends Model
{
    use HasFactory;

    protected $table = 'ref_education_levels';

    protected $primaryKey = 'education_level_id';

}
