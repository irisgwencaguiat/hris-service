<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnVersions extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_versions';

    protected $primaryKey = 'saln_version_id';

    protected $guarded = [];
}
