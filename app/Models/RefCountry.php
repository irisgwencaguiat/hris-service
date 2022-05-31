<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCountry extends Model
{
    use HasFactory;

    protected $table = 'ref_countries';

    protected $primaryKey = 'country_code';

    public $incrementing = false;
}
