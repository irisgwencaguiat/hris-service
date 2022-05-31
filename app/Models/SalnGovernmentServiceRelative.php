<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnGovernmentServiceRelative extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_government_service_relatives';

    protected $primaryKey = 'saln_government_service_relative_id';

    protected $guarded = [];
}
