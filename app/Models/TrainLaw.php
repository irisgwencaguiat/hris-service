<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainLaw extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_train_laws';

    protected $primaryKey = 'train_law_id';

    protected $fillable = [
        'train_law_desc',
        'year_start',
        'year_end',
        'activated'
    ];

    public function taxes()
    {
        return $this->hasMany(TrainLawTax::class, 
            'train_law_id',
            'train_law_id'
        );
    }

    public function affecteds()
    {
        return $this->hasMany(TrainLawAffected::class,
            'train_law_id',
            'train_law_id'
        );
    }
}
