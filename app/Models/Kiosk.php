<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kiosk extends Model
{
    use HasFactory;

    protected $table = 'tbl_kiosks';

    protected $primaryKey = 'kiosk_id';

    protected $guarded = [];
}
