<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhilhealthRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_philhealth_rates';

    protected $primaryKey = 'philhealth_rate_id';

    protected $fillable = [
        'year',
        'premium_rate',
        'ps_rate',
        'gs_rate',
        'activated'
    ];
}
