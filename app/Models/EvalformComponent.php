<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvalformComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evalform_components';
    protected $primaryKey = 'evalform_component_id';
    protected $fillable = [
        'eval_form_id',
        'evalform_category_id',
        'evalform_major_final_output_id',
        'evalform_success_indicator_id',
        'evalform_actual_accomplishment_id',
        'need_comment'
    ];
    protected $appends = [
        'major_final_output',
        'success_indicator',
        'actual_accomplishment'
    ];

    public function getMajorFinalOutputAttribute()
    {
        return EvalformMajorFinalOutput::find($this->evalform_major_final_output_id)->desc;
    }
    public function getSuccessIndicatorAttribute()
    {
        return EvalformSuccessIndicator::find($this->evalform_success_indicator_id)->desc;
    }
    public function getActualAccomplishmentAttribute()
    {
        return ($this->evalform_actual_accomplishment == null) ? null :
            EvalformActualAccomplishment::find($this->evalform_actual_accomplishment_id)->desc;
    }
}
