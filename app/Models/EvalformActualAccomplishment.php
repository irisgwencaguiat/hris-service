<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvalformActualAccomplishment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evalform_actual_accomplishments';
    protected $primaryKey = 'evalform_actual_accomplishment_id';
    protected $fillable = [
        'desc'
    ];
}
