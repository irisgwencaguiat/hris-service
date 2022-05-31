<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefRequirement extends Model
{
    use HasFactory;

    public $table = 'ref_requirements';

    public $primaryKey = 'requirement_id';

    public $guarded = [];
}
