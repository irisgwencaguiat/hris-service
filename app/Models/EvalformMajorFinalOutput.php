<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvalformMajorFinalOutput extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evalform_major_final_outputs';
    protected $primaryKey = 'evalform_major_final_output_id';
    protected $fillable = [
        'desc'
    ];
}
