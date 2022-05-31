<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Benefit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_benefits';

    protected $primaryKey = 'benefit_id';

    protected $fillable = [
        'benefit_desc',
        'amount',
        'activated'
    ];
}
