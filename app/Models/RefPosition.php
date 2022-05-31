<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_positions';

    protected $primaryKey = 'position_id';

    protected $fillable = ['position_name'];

    public function plantilla()
    {
        return $this->belongsTo(
            RefPlantilla::class,
            'position_id',
            'position_id'
        )->withDefault();
    }
}
