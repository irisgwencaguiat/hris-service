<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StepIncrement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_step_increments';

    protected $primaryKey = 'step_increment_id';

    protected $fillable = [
        'step_increment_name'
    ];
}
