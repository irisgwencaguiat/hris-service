<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvalformCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_evalform_categories';
    protected $primaryKey = 'evalform_category_id';
    protected $fillable = [
        'eval_form_id',
        'desc',
        'percentage'
    ];

    public function components()
    {
        return $this->hasMany(EvalformComponent::class, 'evalform_category_id', 'evalform_category_id');
    }
}
