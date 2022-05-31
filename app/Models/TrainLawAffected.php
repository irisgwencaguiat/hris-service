<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainLawAffected extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_train_law_affecteds';

    protected $primaryKey = 'train_law_affected_id';

    protected $fillable = [
        'train_law_id',
        'employment_status_id',
        'position_id'
    ];

    public function employmentStatus()
    {
        return $this->hasOne(RefEmploymentStatus::class, 
            'employment_status_id', 
            'employment_status_id'
        )->withDefault();
    }

    public function position()
    {
        return $this->hasOne(RefPosition::class, 
            'position_id', 
            'position_id'
        )->withDefault();
    }
}
