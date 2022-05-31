<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationSchedule extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tbl_evaluation_schedules';
    protected $primaryKey = 'eval_sched_id';
    protected $fillable = [
        'eval_type',
        'eval_display_start',
        'eval_display_end',
        'date_start',
        'date_end',
        'eval_form_id'
    ];
}
