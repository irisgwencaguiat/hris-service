<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsLearningDevelopment extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_learning_developments';

    protected $primaryKey = 'pds_learning_development_id';

    protected $guarded = [];
}
