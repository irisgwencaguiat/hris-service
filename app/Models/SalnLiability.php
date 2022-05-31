<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnLiability extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_liabilities';

    protected $primaryKey = 'saln_liability_id';

    protected $guarded = [];
}
