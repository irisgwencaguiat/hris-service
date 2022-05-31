<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvalformSuccessIndicator extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evalform_success_indicators';
    protected $primaryKey = 'evalform_success_indicator_id';
    protected $fillable = [
        'desc'
    ];
}
