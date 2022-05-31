<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnChild extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_children';

    protected $primaryKey = 'saln_child_id';

    protected $guarded = [];
}
