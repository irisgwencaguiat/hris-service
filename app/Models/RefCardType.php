<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCardType extends Model
{
    use HasFactory;

    protected $table = 'ref_card_types';

    protected $primaryKey = 'card_type_id';

    protected $guarded = [];
}
