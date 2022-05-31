<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evaluation_forms';
    protected $primaryKey = 'eval_form_id';
    protected $fillable = [
        'form_title'
    ];
    protected $appends = [
        'created_by_name',
        'updated_by_name',
    ];

    public function getCreatedByNameAttribute()
    {
        return ($this->created_by == null) ? null :
            User::find($this->created_by)->last_name;
    }
    public function getUpdatedByNameAttribute()
    {
        return ($this->updated_by == null) ? null :
            User::find($this->updated_by)->last_name;
    }

    public function categories()
    {
        return $this->hasMany(EvalformCategory::class, 'eval_form_id', 'eval_form_id');
    }
}
