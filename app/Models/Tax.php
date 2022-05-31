<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_taxes';

    protected $primaryKey = 'tax_id';

    protected $fillable = [
        'tax_desc',
        'fixed_rate',
        'fixed_amount',
        'has_reference_table',
        'reference_table',
        'activated'
    ];
}
